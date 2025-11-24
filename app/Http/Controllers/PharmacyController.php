<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FirebasePharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /**
     * Afficher la page d'accueil avec la carte des pharmacies
     */
    public function index()
    {
        // Récupérer toutes les pharmacies actives et vérifiées
        $allPharmacies = FirebasePharmacy::all();
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->values();

        return view('pharmacies.index', compact('pharmacies'));
    }

    /**
     * Afficher les détails d'une pharmacie
     */
    public function show($id)
    {
        // Vérifier que l'utilisateur est connecté pour voir les détails
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour voir les détails de la pharmacie.');
        }

        $pharmacy = FirebasePharmacy::findOrFail($id);

        return view('pharmacies.show', compact('pharmacy'));
    }

    /**
     * Rechercher des pharmacies par proximité
     */
    public function search(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:50'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // Rayon par défaut de 10km

        // Rechercher les pharmacies à proximité
        $allPharmacies = FirebasePharmacy::scopeNearby(null, $latitude, $longitude, $radius);
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->filter(function ($pharmacy) {
                // Vérifier que les coordonnées sont valides
                if ($pharmacy->latitude === null || $pharmacy->longitude === null) {
                    return false;
                }
                
                $lat = (float) $pharmacy->latitude;
                $lng = (float) $pharmacy->longitude;
                
                return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
            })
            ->map(function ($pharmacy) {
                // Convertir l'objet en tableau
                $data = $pharmacy->toArray();
                
                // S'assurer que l'ID est présent
                if (!isset($data['id']) && isset($pharmacy->id)) {
                    $data['id'] = $pharmacy->id;
                }
                
                // Convertir les coordonnées en nombres
                if (isset($data['latitude'])) {
                    $data['latitude'] = is_numeric($data['latitude']) ? (float) $data['latitude'] : null;
                }
                if (isset($data['longitude'])) {
                    $data['longitude'] = is_numeric($data['longitude']) ? (float) $data['longitude'] : null;
                }
                
                return $data;
            })
            ->values();

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmacies,
            'count' => $pharmacies->count()
        ]);
    }

    /**
     * Rechercher des pharmacies par ville
     * Peut également utiliser la position de l'utilisateur pour améliorer la recherche
     */
    public function searchByCity(Request $request)
    {
        try {
            $request->validate([
                'city' => 'required|string|min:2|max:100',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:50'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Utiliser le cache pour optimiser les performances
            $cacheKey = 'pharmacies_search_' . md5($request->city . ($request->latitude ?? '') . ($request->longitude ?? '') . ($request->radius ?? ''));
            $cacheDuration = 60; // 1 minute de cache pour les recherches
            
            // Vérifier le cache
            if (\Cache::has($cacheKey)) {
                $cached = \Cache::get($cacheKey);
                return response()->json([
                    'success' => true,
                    'pharmacies' => $cached['pharmacies'],
                    'count' => $cached['count'],
                    'cached' => true,
                    'search_params' => [
                        'city' => $request->city,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude
                    ]
                ]);
            }
            
            // Récupérer toutes les pharmacies (utilise le cache interne de FirebaseService)
            $allPharmacies = FirebasePharmacy::all();
            
            if (!$allPharmacies || $allPharmacies->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune pharmacie disponible dans la base de données',
                    'pharmacies' => [],
                    'count' => 0
                ], 404);
            }
            
            // Filtrer les pharmacies actives et vérifiées
            $activePharmacies = $allPharmacies
                ->where('is_active', true)
                ->where('is_verified', true);
            
            // Recherche par ville (insensible à la casse et normalisée)
            $searchCity = mb_strtolower(trim($request->city));
            $pharmacies = $activePharmacies->filter(function ($pharmacy) use ($searchCity, $request) {
                $pharmacyCity = mb_strtolower(trim($pharmacy->city ?? ''));
                
                // Recherche exacte ou partielle
                if (empty($pharmacyCity) || strpos($pharmacyCity, $searchCity) === false) {
                    return false;
                }
                
                // Vérifier que les coordonnées sont valides si elles existent
                if ($pharmacy->latitude !== null && $pharmacy->longitude !== null) {
                    $lat = (float) $pharmacy->latitude;
                    $lng = (float) $pharmacy->longitude;
                    
                    // Valider les coordonnées
                    if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                        // Coordonnées invalides, mais on peut quand même retourner la pharmacie
                        // (elle sera filtrée côté client si nécessaire)
                    }
                }
                
                // Si une position est fournie, filtrer aussi par proximité
                if ($request->has('latitude') && $request->has('longitude') && 
                    $pharmacy->latitude !== null && $pharmacy->longitude !== null) {
                    $lat = (float) $pharmacy->latitude;
                    $lng = (float) $pharmacy->longitude;
                    
                    // Vérifier que les coordonnées sont valides
                    if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                        return false; // Coordonnées invalides, exclure de la recherche par proximité
                    }
                    
                    $radius = $request->radius ?? 50;
                    $distance = FirebasePharmacy::calculateDistance(
                        $request->latitude,
                        $request->longitude,
                        $lat,
                        $lng
                    );
                    
                    // Ajouter la distance à la pharmacie pour le tri
                    $pharmacy->distance = $distance;
                    
                    return $distance <= $radius;
                }
                
                return true;
            })->values();
            
            // Trier par distance si une position est fournie
            if ($request->has('latitude') && $request->has('longitude')) {
                $pharmacies = $pharmacies->sortBy('distance')->values();
            }
            
            // Convertir les objets FirebaseModel en tableaux associatifs pour JSON
            $pharmacies = $pharmacies->map(function ($pharmacy) {
                // Convertir l'objet en tableau
                $data = $pharmacy->toArray();
                
                // S'assurer que les coordonnées sont des nombres
                if (isset($data['latitude']) && $data['latitude'] !== null && $data['latitude'] !== '') {
                    $data['latitude'] = is_numeric($data['latitude']) ? (float) $data['latitude'] : null;
                } else {
                    $data['latitude'] = null;
                }
                
                if (isset($data['longitude']) && $data['longitude'] !== null && $data['longitude'] !== '') {
                    $data['longitude'] = is_numeric($data['longitude']) ? (float) $data['longitude'] : null;
                } else {
                    $data['longitude'] = null;
                }
                
                // S'assurer que l'ID est présent
                if (!isset($data['id']) && isset($pharmacy->id)) {
                    $data['id'] = $pharmacy->id;
                }
                
                return $data;
            });
            
            // Mettre en cache les résultats
            \Cache::put($cacheKey, [
                'pharmacies' => $pharmacies,
                'count' => $pharmacies->count()
            ], $cacheDuration);

            return response()->json([
                'success' => true,
                'pharmacies' => $pharmacies,
                'count' => $pharmacies->count(),
                'search_params' => [
                    'city' => $request->city,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans searchByCity: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la recherche: ' . $e->getMessage(),
                'pharmacies' => [],
                'count' => 0
            ], 500);
        }
    }

    /**
     * Obtenir les pharmacies pour l'API (pour la carte)
     */
    public function getPharmaciesForMap()
    {
        $allPharmacies = FirebasePharmacy::all();
        $pharmacies = $allPharmacies
            ->where('is_active', true)
            ->where('is_verified', true)
            ->map(function ($pharmacy) {
                // Convertir les coordonnées en nombres flottants
                $latitude = null;
                $longitude = null;
                
                if ($pharmacy->latitude !== null) {
                    $latitude = is_numeric($pharmacy->latitude) ? (float) $pharmacy->latitude : null;
                }
                
                if ($pharmacy->longitude !== null) {
                    $longitude = is_numeric($pharmacy->longitude) ? (float) $pharmacy->longitude : null;
                }
                
                return [
                    'id' => $pharmacy->id,
                    'name' => $pharmacy->name ?? '',
                    'address' => $pharmacy->address ?? '',
                    'city' => $pharmacy->city ?? '',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'phone' => $pharmacy->phone ?? '',
                    'whatsapp_number' => $pharmacy->whatsapp_number ?? '',
                ];
            })
            ->filter(function ($pharmacy) {
                // Filtrer les pharmacies sans coordonnées valides
                return $pharmacy['latitude'] !== null && 
                       $pharmacy['longitude'] !== null &&
                       $pharmacy['latitude'] >= -90 && 
                       $pharmacy['latitude'] <= 90 &&
                       $pharmacy['longitude'] >= -180 && 
                       $pharmacy['longitude'] <= 180;
            })
            ->values();

        return response()->json([
            'success' => true,
            'pharmacies' => $pharmacies
        ]);
    }

    /**
     * Afficher la page de recherche avancée
     */
    public function searchPage(Request $request)
    {
        $pharmacies = collect();
        
        if ($request->has('search_type')) {
            if ($request->search_type === 'proximity' && $request->has('latitude') && $request->has('longitude')) {
                // Recherche par proximité
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $radius = $request->radius ?? 10;
                
                $allPharmacies = FirebasePharmacy::scopeNearby(null, $latitude, $longitude, $radius);
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->values();
                    
            } elseif ($request->search_type === 'city' && $request->has('search_city')) {
                // Recherche par ville
                $allPharmacies = FirebasePharmacy::all();
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->filter(function ($pharmacy) use ($request) {
                        return stripos($pharmacy->city ?? '', $request->search_city) !== false;
                    })
                    ->values();
                    
            } elseif ($request->search_type === 'name' && $request->has('search_name')) {
                // Recherche par nom
                $allPharmacies = FirebasePharmacy::all();
                $pharmacies = $allPharmacies
                    ->where('is_active', true)
                    ->where('is_verified', true)
                    ->filter(function ($pharmacy) use ($request) {
                        return stripos($pharmacy->name ?? '', $request->search_name) !== false;
                    })
                    ->values();
            }
            
            // Filtrer par services si spécifiés
            if ($request->has('services') && is_array($request->services)) {
                $pharmacies = $pharmacies->filter(function ($pharmacy) use ($request) {
                    if (!$pharmacy->services) return false;
                    return !empty(array_intersect($request->services, $pharmacy->services));
                });
            }
        }
        
        return view('pharmacies.search', compact('pharmacies'));
    }
}
