<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_completed' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relation avec les pharmacies (pour les pharmaciens)
     */
    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class, 'pharmacist_id');
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
}
