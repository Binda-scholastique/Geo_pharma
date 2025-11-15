<?php

namespace App\Models;

class FirebaseAuthorizationNumber extends FirebaseModel
{
    protected $collection = 'authorization_numbers';
    
    protected $fillable = [
        'number',
        'is_valid',
        'expires_at',
        'pharmacist_name',
        'pharmacy_name',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'expires_at' => 'date',
    ];

    /**
     * Scope pour les numéros valides
     */
    public function scopeValid($query)
    {
        return $query->where('is_valid', '=', true);
    }

    /**
     * Scope pour les numéros non expirés
     */
    public function scopeNotExpired($query)
    {
        $now = now()->toIso8601String();
        $results = static::all()->filter(function ($item) use ($now) {
            if (!$item->expires_at) {
                return true; // Pas de date d'expiration = jamais expiré
            }
            $expiresAt = is_string($item->expires_at) ? new \DateTime($item->expires_at) : $item->expires_at;
            return $expiresAt > now();
        });
        
        return $results;
    }

    /**
     * Vérifier si le numéro est valide et non expiré
     */
    public function isValidAndNotExpired()
    {
        if (!$this->is_valid) {
            return false;
        }
        
        if (!$this->expires_at) {
            return true; // Pas de date d'expiration = jamais expiré
        }
        
        $expiresAt = is_string($this->expires_at) ? new \DateTime($this->expires_at) : $this->expires_at;
        return $expiresAt > now();
    }
}

