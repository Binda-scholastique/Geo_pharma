<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class FirebaseService
{
    protected $projectId;
    protected $credentials;
    protected $accessToken;
    protected $baseUrl;

    public function __construct()
    {
        try {
            $credentialsPath = config('firebase.credentials.file');
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception("Fichier de credentials Firebase introuvable : {$credentialsPath}");
            }

            $this->projectId = config('firebase.project_id');
            $this->credentials = json_decode(file_get_contents($credentialsPath), true);
            $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
            
            // Obtenir un token d'accès
            $this->refreshAccessToken();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'initialisation de Firebase : ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtenir un token d'accès OAuth2
     */
    protected function refreshAccessToken()
    {
        try {
            $credentials = new ServiceAccountCredentials(
                ['https://www.googleapis.com/auth/cloud-platform'],
                $this->credentials
            );
            
            $token = $credentials->fetchAuthToken();
            $this->accessToken = $token['access_token'];
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'obtention du token d\'accès : ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Convertir un tableau PHP en format Firestore
     */
    protected function convertToFirestoreValue($value)
    {
        if (is_null($value)) {
            return ['nullValue' => null];
        } elseif (is_bool($value)) {
            return ['booleanValue' => $value];
        } elseif (is_int($value)) {
            return ['integerValue' => (string) $value];
        } elseif (is_float($value)) {
            return ['doubleValue' => $value];
        } elseif (is_string($value)) {
            return ['stringValue' => $value];
        } elseif (is_array($value)) {
            if (array_keys($value) === range(0, count($value) - 1)) {
                // Array indexé
                return ['arrayValue' => ['values' => array_map([$this, 'convertToFirestoreValue'], $value)]];
            } else {
                // Array associatif (map)
                $mapValues = [];
                foreach ($value as $k => $v) {
                    $mapValues[$k] = $this->convertToFirestoreValue($v);
                }
                return ['mapValue' => ['fields' => $mapValues]];
            }
        } elseif ($value instanceof \DateTime) {
            return ['timestampValue' => $value->format('Y-m-d\TH:i:s\Z')];
        }
        
        return ['stringValue' => (string) $value];
    }

    /**
     * Convertir un format Firestore en tableau PHP
     */
    protected function convertFromFirestoreValue($firestoreValue)
    {
        if (isset($firestoreValue['nullValue'])) {
            return null;
        } elseif (isset($firestoreValue['booleanValue'])) {
            return $firestoreValue['booleanValue'];
        } elseif (isset($firestoreValue['integerValue'])) {
            return (int) $firestoreValue['integerValue'];
        } elseif (isset($firestoreValue['doubleValue'])) {
            return (float) $firestoreValue['doubleValue'];
        } elseif (isset($firestoreValue['stringValue'])) {
            return $firestoreValue['stringValue'];
        } elseif (isset($firestoreValue['arrayValue'])) {
            return array_map([$this, 'convertFromFirestoreValue'], $firestoreValue['arrayValue']['values'] ?? []);
        } elseif (isset($firestoreValue['mapValue'])) {
            $result = [];
            foreach ($firestoreValue['mapValue']['fields'] ?? [] as $key => $value) {
                $result[$key] = $this->convertFromFirestoreValue($value);
            }
            return $result;
        } elseif (isset($firestoreValue['timestampValue'])) {
            return new \DateTime($firestoreValue['timestampValue']);
        }
        
        return null;
    }

    /**
     * Créer un document dans une collection
     */
    public function create(string $collection, array $data, ?string $documentId = null)
    {
        try {
            $fields = [];
            foreach ($data as $key => $value) {
                $fields[$key] = $this->convertToFirestoreValue($value);
            }

            $url = $this->baseUrl . '/' . $collection;
            
            if ($documentId) {
                $url .= '/' . $documentId;
            }

            $response = Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'fields' => $fields
                ]);

            if ($response->failed()) {
                throw new \Exception('Erreur Firebase : ' . $response->body());
            }

            $result = $response->json();
            $newId = $documentId ?: basename($result['name']);
            
            // Invalider le cache après création
            $this->clearCache($collection);
            
            return $newId;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la création dans {$collection}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lire un document par son ID (avec cache)
     */
    public function read(string $collection, string $documentId, $useCache = true)
    {
        $cacheKey = "firebase_document_{$collection}_{$documentId}";
        $cacheDuration = 300; // 5 minutes
        
        // Essayer de récupérer depuis le cache
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $url = $this->baseUrl . '/' . $collection . '/' . $documentId;

            $response = Http::withToken($this->accessToken)
                ->get($url);

            if ($response->status() === 404) {
                return null;
            }

            if ($response->failed()) {
                throw new \Exception('Erreur Firebase : ' . $response->body());
            }

            $result = $response->json();
            $data = [];
            
            if (isset($result['fields'])) {
                foreach ($result['fields'] as $key => $value) {
                    $data[$key] = $this->convertFromFirestoreValue($value);
                }
            }
            
            $data['id'] = $documentId;
            
            // Mettre en cache
            if ($useCache) {
                Cache::put($cacheKey, $data, $cacheDuration);
            }
            
            return $data;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la lecture de {$collection}/{$documentId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour un document
     */
    public function update(string $collection, string $documentId, array $data)
    {
        try {
            $fields = [];
            foreach ($data as $key => $value) {
                $fields[$key] = $this->convertToFirestoreValue($value);
            }

            $url = $this->baseUrl . '/' . $collection . '/' . $documentId;

            $response = Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->patch($url, [
                    'fields' => $fields
                ]);

            if ($response->failed()) {
                throw new \Exception('Erreur Firebase : ' . $response->body());
            }

            // Invalider le cache après mise à jour
            $this->clearCache($collection, $documentId);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour de {$collection}/{$documentId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Supprimer un document
     */
    public function delete(string $collection, string $documentId)
    {
        try {
            $url = $this->baseUrl . '/' . $collection . '/' . $documentId;

            $response = Http::withToken($this->accessToken)
                ->delete($url);

            if ($response->failed() && $response->status() !== 404) {
                throw new \Exception('Erreur Firebase : ' . $response->body());
            }

            // Invalider le cache après suppression
            $this->clearCache($collection, $documentId);

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression de {$collection}/{$documentId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupérer tous les documents d'une collection (avec cache)
     */
    public function getAll(string $collection, $useCache = true)
    {
        $cacheKey = "firebase_collection_{$collection}";
        $cacheDuration = 300; // 5 minutes
        
        // Essayer de récupérer depuis le cache
        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $url = $this->baseUrl . '/' . $collection;

            $response = Http::withToken($this->accessToken)
                ->get($url);

            if ($response->failed()) {
                throw new \Exception('Erreur Firebase : ' . $response->body());
            }

            $result = $response->json();
            $documents = [];

            if (isset($result['documents'])) {
                foreach ($result['documents'] as $doc) {
                    $data = [];
                    if (isset($doc['fields'])) {
                        foreach ($doc['fields'] as $key => $value) {
                            $data[$key] = $this->convertFromFirestoreValue($value);
                        }
                    }
                    $data['id'] = basename($doc['name']);
                    $documents[] = $data;
                }
            }

            // Mettre en cache
            if ($useCache) {
                Cache::put($cacheKey, $documents, $cacheDuration);
            }

            return $documents;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la récupération de {$collection}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Invalider le cache d'une collection
     */
    public function clearCache(string $collection, $documentId = null)
    {
        if ($documentId) {
            // Invalider le cache d'un document spécifique
            $cacheKey = "firebase_document_{$collection}_{$documentId}";
            Cache::forget($cacheKey);
        }
        
        // Toujours invalider le cache de la collection complète
        $cacheKey = "firebase_collection_{$collection}";
        Cache::forget($cacheKey);
    }
    
    /**
     * Invalider tous les caches Firebase
     */
    public function clearAllCache()
    {
        $collections = ['users', 'pharmacies', 'authorization_numbers'];
        foreach ($collections as $collection) {
            $this->clearCache($collection);
        }
    }

    /**
     * Rechercher des documents avec des conditions (simplifié - utilise getAll puis filtre)
     */
    public function where(string $collection, string $field, string $operator, $value)
    {
        try {
            // Pour simplifier, on récupère tous les documents et on filtre
            // Note: Pour de grandes collections, il faudrait utiliser les requêtes Firestore
            $allDocs = $this->getAll($collection);
            $results = [];

            foreach ($allDocs as $doc) {
                if (!isset($doc[$field])) {
                    continue;
                }

                $docValue = $doc[$field];
                $match = false;

                switch ($operator) {
                    case '=':
                    case '==':
                        $match = $docValue == $value;
                        break;
                    case '!=':
                        $match = $docValue != $value;
                        break;
                    case '>':
                        $match = $docValue > $value;
                        break;
                    case '>=':
                        $match = $docValue >= $value;
                        break;
                    case '<':
                        $match = $docValue < $value;
                        break;
                    case '<=':
                        $match = $docValue <= $value;
                        break;
                }

                if ($match) {
                    $results[] = $doc;
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error("Erreur lors de la recherche dans {$collection}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier la connexion à Firebase
     */
    public function testConnection(): bool
    {
        try {
            // Tester en créant un document de test puis le supprimant
            $testCollection = '__test_connection__';
            $testDocId = 'test_' . time();
            
            // Créer un document de test
            $testData = ['test' => true, 'timestamp' => time()];
            $fields = [];
            foreach ($testData as $key => $value) {
                $fields[$key] = $this->convertToFirestoreValue($value);
            }
            
            $url = $this->baseUrl . '/' . $testCollection . '/' . $testDocId;
            
            $response = Http::withToken($this->accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->put($url, [
                    'fields' => $fields
                ]);

            if ($response->failed()) {
                $errorBody = $response->body();
                // Si c'est une erreur 404, cela peut signifier que la collection n'existe pas encore, ce qui est OK
                // Si c'est une erreur d'authentification (401, 403), c'est un problème
                if ($response->status() === 401 || $response->status() === 403) {
                    Log::error('Test de connexion Firebase échoué - Authentification : ' . $response->status() . ' - ' . substr($errorBody, 0, 200));
                    return false;
                }
                // Pour 404, on peut quand même considérer que la connexion fonctionne
                // car cela signifie que l'authentification a réussi
                if ($response->status() === 404) {
                    return true; // L'API répond, l'authentification fonctionne
                }
                Log::error('Test de connexion Firebase échoué : ' . $response->status() . ' - ' . substr($errorBody, 0, 200));
                return false;
            }

            // Supprimer le document de test
            try {
                Http::withToken($this->accessToken)->delete($url);
            } catch (\Exception $e) {
                // Ignorer les erreurs de suppression
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Test de connexion Firebase échoué : ' . $e->getMessage());
            return false;
        }
    }
}
