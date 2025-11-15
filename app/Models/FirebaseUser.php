<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FirebaseUser extends FirebaseModel implements Authenticatable
{
    use AuthenticatableTrait, HasApiTokens, Notifiable;

    protected $collection = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'authorization_number',
        'profile_completed',
        'latitude',
        'longitude',
        'address',
        'city',
        'postal_code',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_completed' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Récupérer un utilisateur par email (pour l'authentification)
     */
    public static function whereEmail($email)
    {
        if (empty($email)) {
            return null;
        }
        
        // Récupérer tous les utilisateurs et filtrer par email (insensible à la casse)
        $allUsers = static::all();
        $user = $allUsers->first(function ($user) use ($email) {
            return strtolower(trim($user->email ?? '')) === strtolower(trim($email));
        });
        
        return $user;
    }

    /**
     * Récupérer les pharmacies d'un pharmacien
     */
    public function pharmacies()
    {
        if (!$this->isPharmacist()) {
            return collect([]);
        }
        
        // Récupérer toutes les pharmacies et filtrer par pharmacist_id
        $allPharmacies = FirebasePharmacy::all();
        $userPharmacies = $allPharmacies->where('pharmacist_id', $this->getKey());
        
        // S'assurer de retourner toujours une collection, même vide
        return $userPharmacies ?? collect([]);
    }
    
    /**
     * Accesseur pour les pharmacies (compatibilité avec l'accès en propriété)
     */
    public function getPharmaciesAttribute()
    {
        if (!$this->isPharmacist()) {
            return collect([]);
        }
        
        // Récupérer toutes les pharmacies et filtrer par pharmacist_id
        $allPharmacies = FirebasePharmacy::all();
        $userPharmacies = $allPharmacies->where('pharmacist_id', $this->getKey());
        
        // S'assurer de retourner toujours une collection, même vide
        return $userPharmacies ?? collect([]);
    }

    /**
     * Vérifier si l'utilisateur est un pharmacien
     */
    public function isPharmacist()
    {
        return $this->role === 'pharmacist';
    }

    /**
     * Vérifier si l'utilisateur est un utilisateur normal
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Vérifier si l'utilisateur est un administrateur
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Obtenir le nom de l'utilisateur pour l'authentification
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Obtenir l'identifiant unique de l'utilisateur
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Obtenir le mot de passe de l'utilisateur
     * Accès direct aux attributes pour éviter que __get() retourne null (password est dans $hidden)
     */
    public function getAuthPassword()
    {
        return $this->attributes['password'] ?? null;
    }

    /**
     * Obtenir le token "remember me"
     * Accès direct aux attributes pour éviter que __get() retourne null
     */
    public function getRememberToken()
    {
        return $this->attributes['remember_token'] ?? null;
    }

    /**
     * Définir le token "remember me"
     */
    public function setRememberToken($value)
    {
        $this->attributes['remember_token'] = $value;
    }

    /**
     * Obtenir le nom de la colonne "remember me"
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    
    /**
     * Forcer la mise à jour du mot de passe
     * Méthode utilitaire pour réinitialiser le password
     */
    public function setPassword($password)
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
        return $this->save();
    }
}

