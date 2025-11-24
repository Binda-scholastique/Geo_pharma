<?php

namespace App\Models;

use App\Services\FirebaseService;
use Illuminate\Support\Collection;

abstract class FirebaseModel
{
    protected $firebase;
    protected $collection;
    protected $attributes = [];
    protected $fillable = [];
    protected $hidden = [];
    protected $casts = [];
    protected $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->firebase = app(FirebaseService::class);
        $this->fill($attributes);
    }

    /**
     * Remplir le modèle avec des attributs
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable) || empty($this->fillable)) {
                $this->attributes[$key] = $this->castAttribute($key, $value);
            }
        }
        return $this;
    }

    /**
     * Caster un attribut selon les casts définis
     */
    protected function castAttribute($key, $value)
    {
        if (isset($this->casts[$key])) {
            switch ($this->casts[$key]) {
                case 'boolean':
                    return (bool) $value;
                case 'integer':
                    return (int) $value;
                case 'float':
                case 'decimal:8':
                    if ($value === null || $value === '' || $value === 'null') {
                        return null;
                    }
                    return (float) $value;
                case 'array':
                    return is_array($value) ? $value : json_decode($value, true) ?? [];
                case 'datetime':
                case 'date':
                    return $value ? (is_string($value) ? new \DateTime($value) : $value) : null;
                default:
                    return $value;
            }
        }
        return $value;
    }

    /**
     * Obtenir un attribut
     */
    public function __get($key)
    {
        if (in_array($key, $this->hidden)) {
            return null;
        }
        
        // Vérifier s'il existe un accesseur (getXxxAttribute)
        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key))) . 'Attribute';
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        
        // Gérer les timestamps pour compatibilité avec Carbon
        if (in_array($key, ['created_at', 'updated_at'])) {
            $value = $this->attributes[$key] ?? null;
            if ($value === null) {
                return null;
            }
            // Si c'est une chaîne, la convertir en Carbon
            if (is_string($value)) {
                try {
                    return \Carbon\Carbon::parse($value);
                } catch (\Exception $e) {
                    return null;
                }
            }
            // Si c'est déjà un DateTime/Carbon, le retourner
            if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
                return $value;
            }
            return null;
        }
        
        // Récupérer la valeur brute
        $value = $this->attributes[$key] ?? null;
        
        // Appliquer le cast si défini
        if ($value !== null && isset($this->casts[$key])) {
            return $this->castAttribute($key, $value);
        }
        
        return $value;
    }

    /**
     * Définir un attribut
     */
    public function __set($key, $value)
    {
        // Permettre toujours le password même s'il n'est pas dans fillable (pour la réparation)
        if ($key === 'password' || in_array($key, $this->fillable) || empty($this->fillable)) {
            $this->attributes[$key] = $this->castAttribute($key, $value);
        }
    }

    /**
     * Vérifier si un attribut existe
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Obtenir tous les attributs
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Obtenir les attributs pour la sauvegarde
     */
    public function toArray()
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            // Ne pas exclure le password car il doit être sauvegardé
            // Les champs hidden sont exclus seulement pour l'affichage, pas pour la sauvegarde
            if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
                $attributes[$key] = $value->toIso8601String();
            } else {
                $attributes[$key] = $value;
            }
        }
        
        // S'assurer que l'ID est toujours inclus
        if (isset($this->attributes['id']) && !isset($attributes['id'])) {
            $attributes['id'] = $this->attributes['id'];
        }
        
        return $attributes;
    }

    /**
     * Trouver un document par ID
     */
    public static function find($id)
    {
        $model = new static();
        $data = $model->firebase->read($model->collection, (string) $id);
        
        if (!$data) {
            return null;
        }
        
        // Convertir les timestamps en Carbon si présents
        if (isset($data['created_at']) && is_string($data['created_at'])) {
            try {
                $data['created_at'] = \Carbon\Carbon::parse($data['created_at']);
            } catch (\Exception $e) {
                // Garder la valeur originale si la conversion échoue
            }
        }
        if (isset($data['updated_at']) && is_string($data['updated_at'])) {
            try {
                $data['updated_at'] = \Carbon\Carbon::parse($data['updated_at']);
            } catch (\Exception $e) {
                // Garder la valeur originale si la conversion échoue
            }
        }
        
        $model->attributes = $data;
        $model->exists = true;
        return $model;
    }

    /**
     * Trouver ou échouer
     */
    public static function findOrFail($id)
    {
        $model = static::find($id);
        if (!$model) {
            throw new \Exception("Modèle non trouvé avec l'ID: {$id}");
        }
        return $model;
    }

    /**
     * Récupérer tous les documents
     */
    public static function all()
    {
        $model = new static();
        $data = $model->firebase->getAll($model->collection);
        
        return Collection::make($data)->map(function ($item) {
            // Convertir les timestamps en Carbon si présents
            if (isset($item['created_at']) && is_string($item['created_at'])) {
                try {
                    $item['created_at'] = \Carbon\Carbon::parse($item['created_at']);
                } catch (\Exception $e) {
                    // Garder la valeur originale si la conversion échoue
                }
            }
            if (isset($item['updated_at']) && is_string($item['updated_at'])) {
                try {
                    $item['updated_at'] = \Carbon\Carbon::parse($item['updated_at']);
                } catch (\Exception $e) {
                    // Garder la valeur originale si la conversion échoue
                }
            }
            
            $instance = new static();
            $instance->attributes = $item;
            $instance->exists = true;
            return $instance;
        });
    }

    /**
     * Créer une nouvelle instance de requête
     */
    public static function query()
    {
        return new static();
    }

    /**
     * Sauvegarder le modèle
     */
    public function save()
    {
        $data = $this->toArray();
        
        // Retirer l'ID des données si présent
        $id = $this->attributes['id'] ?? null;
        unset($data['id']);
        
        // Log pour débogage (désactiver en production)
        if (config('app.debug')) {
            \Log::debug('Sauvegarde Firebase', [
                'collection' => $this->collection,
                'id' => $id,
                'data_keys' => array_keys($data),
                'has_password' => isset($data['password']),
                'password_value' => isset($data['password']) ? (strlen($data['password']) > 0 ? 'PRÉSENT (' . strlen($data['password']) . ' chars)' : 'VIDE') : 'ABSENT',
                'all_attributes' => array_keys($this->attributes),
            ]);
        }
        
        if ($this->exists && $id) {
            // Mise à jour
            $data['updated_at'] = now()->toIso8601String();
            $this->attributes['updated_at'] = now();
            $this->firebase->update($this->collection, (string) $id, $data);
        } else {
            // Création
            $now = now();
            $data['created_at'] = $now->toIso8601String();
            $data['updated_at'] = $now->toIso8601String();
            $this->attributes['created_at'] = $now;
            $this->attributes['updated_at'] = $now;
            $newId = $this->firebase->create($this->collection, $data, $id);
            $this->attributes['id'] = $newId;
            $this->exists = true;
        }
        
        return true;
    }

    /**
     * Supprimer le modèle
     */
    public function delete()
    {
        if (!$this->exists || !isset($this->attributes['id'])) {
            return false;
        }
        
        $this->firebase->delete($this->collection, (string) $this->attributes['id']);
        $this->exists = false;
        return true;
    }

    /**
     * Rechercher par champ
     */
    public function where($field, $operator, $value = null)
    {
        // Si seulement 2 paramètres, c'est une égalité
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $results = $this->firebase->where($this->collection, $field, $operator, $value);
        
        return Collection::make($results)->map(function ($item) {
            // Convertir les timestamps en Carbon si présents
            if (isset($item['created_at']) && is_string($item['created_at'])) {
                try {
                    $item['created_at'] = \Carbon\Carbon::parse($item['created_at']);
                } catch (\Exception $e) {
                    // Garder la valeur originale si la conversion échoue
                }
            }
            if (isset($item['updated_at']) && is_string($item['updated_at'])) {
                try {
                    $item['updated_at'] = \Carbon\Carbon::parse($item['updated_at']);
                } catch (\Exception $e) {
                    // Garder la valeur originale si la conversion échoue
                }
            }
            
            $instance = new static();
            $instance->attributes = $item;
            $instance->exists = true;
            return $instance;
        });
    }

    /**
     * Obtenir une collection (pour compatibilité avec les scopes)
     */
    public function get()
    {
        return static::all();
    }

    /**
     * Paginer les résultats
     */
    public function paginate($perPage = 15, $page = null)
    {
        $page = $page ?: request()->get('page', 1);
        $all = static::all();
        $total = $all->count();
        $items = $all->forPage($page, $perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Limiter le nombre de résultats
     */
    public function limit($limit)
    {
        return static::all()->take($limit);
    }

    /**
     * Trier les résultats
     */
    public function orderBy($field, $direction = 'asc')
    {
        $all = static::all();
        return $all->sortBy($field, SORT_REGULAR, $direction === 'desc');
    }

    /**
     * Charger une relation (pour compatibilité)
     */
    public function load($relations)
    {
        // Les relations sont chargées à la demande dans les modèles
        return $this;
    }

    /**
     * Précharger des relations (pour compatibilité)
     */
    public static function with($relations)
    {
        // Les relations sont chargées à la demande
        return new static();
    }

    /**
     * Compter les documents
     */
    public function count()
    {
        $all = static::all();
        return $all->count();
    }

    /**
     * Obtenir l'ID du modèle
     */
    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Obtenir l'ID (pour compatibilité Eloquent)
     */
    public function getKey()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Obtenir le nom de la clé primaire
     */
    public function getKeyName()
    {
        return 'id';
    }
}

