<?php

namespace App\Models;

class FirebasePharmacy extends FirebaseModel
{
    protected $collection = 'pharmacies';
    
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

    protected $casts = [
        'opening_hours' => 'array',
        'services' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Récupérer le pharmacien propriétaire
     */
    public function pharmacist()
    {
        if (!$this->pharmacist_id) {
            return null;
        }
        
        return FirebaseUser::find($this->pharmacist_id);
    }

    /**
     * Scope pour les pharmacies actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', '=', true);
    }

    /**
     * Scope pour les pharmacies vérifiées
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', '=', true);
    }

    /**
     * Scope pour rechercher par proximité géographique
     * Note: Firestore ne supporte pas nativement les requêtes géographiques complexes
     * Cette méthode filtre après récupération
     */
    public static function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        // Récupérer toutes les pharmacies actives et vérifiées
        $all = static::all()
            ->filter(function ($pharmacy) use ($latitude, $longitude, $radius) {
                if (!$pharmacy->latitude || !$pharmacy->longitude) {
                    return false;
                }
                
                // Calculer la distance en km (formule de Haversine)
                $distance = static::calculateDistance(
                    $latitude,
                    $longitude,
                    (float) $pharmacy->latitude,
                    (float) $pharmacy->longitude
                );
                
                return $distance <= $radius;
            })
            ->map(function ($pharmacy) use ($latitude, $longitude) {
                // Ajouter la distance calculée
                $pharmacy->distance = static::calculateDistance(
                    $latitude,
                    $longitude,
                    (float) $pharmacy->latitude,
                    (float) $pharmacy->longitude
                );
                return $pharmacy;
            })
            ->sortBy('distance')
            ->values();
        
        return $all;
    }

    /**
     * Calculer la distance entre deux points
     */
    protected static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Rayon de la Terre en km
        
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
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

