<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'phone',
        'email',
        'whatsapp_number',
        'opening_hours',
        'services',
        'is_active',
        'is_verified',
        'pharmacist_id',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'opening_hours' => 'array',
        'services' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relation avec le pharmacien propriétaire
     */
    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    /**
     * Scope pour les pharmacies actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les pharmacies vérifiées
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope pour rechercher par proximité géographique
     */
    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        return $query->selectRaw("*, 
            (6371 * acos(cos(radians(?)) 
            * cos(radians(latitude)) 
            * cos(radians(longitude) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(latitude)))) AS distance", 
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }

    /**
     * Obtenir l'URL WhatsApp formatée
     */
    public function getWhatsappUrlAttribute()
    {
        if ($this->whatsapp_number) {
            $phone = preg_replace('/[^0-9]/', '', $this->whatsapp_number);
            $message = $this->getPredefinedMessage();
            return "https://wa.me/" . $phone . "?text=" . urlencode($message);
        }
        return null;
    }

    /**
     * Obtenir le message prédéfini pour le contact
     */
    public function getPredefinedMessage()
    {
        $hour = (int) date('H');
        $greeting = ($hour >= 18 || $hour < 6) ? 'Bonsoir' : 'Bonjour';
        
        $userName = auth()->check() ? auth()->user()->name : 'un utilisateur';
        
        $message = "{$greeting} {$this->name}, je suis {$userName} depuis l'application GeoPharma. Je souhaite obtenir des informations sur vos services et vos horaires d'ouverture. Pourriez-vous me renseigner ?";
        
        return $message;
    }

    /**
     * Obtenir l'URL email avec message prédéfini
     */
    public function getEmailUrlAttribute()
    {
        if ($this->email) {
            $subject = "Contact depuis GeoPharma";
            $message = $this->getPredefinedMessage();
            return "mailto:" . $this->email . "?subject=" . urlencode($subject) . "&body=" . urlencode($message);
        }
        return null;
    }
}
