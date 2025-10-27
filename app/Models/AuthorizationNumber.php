<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizationNumber extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'number',
        'is_valid',
        'expires_at',
        'pharmacist_name',
        'pharmacy_name',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'is_valid' => 'boolean',
        'expires_at' => 'date',
    ];

    /**
     * Scope pour les numéros valides
     */
    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }

    /**
     * Scope pour les numéros non expirés
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Vérifier si le numéro est valide et non expiré
     */
    public function isValidAndNotExpired()
    {
        return $this->is_valid && 
               ($this->expires_at === null || $this->expires_at > now());
    }
}
