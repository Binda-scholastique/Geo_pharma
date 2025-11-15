<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\FirebaseUser;
use Illuminate\Support\Facades\Hash;

class FirebaseUserProvider implements UserProvider
{
    /**
     * Récupérer un utilisateur par son identifiant unique
     */
    public function retrieveById($identifier)
    {
        return FirebaseUser::find($identifier);
    }

    /**
     * Récupérer un utilisateur par son identifiant unique et "remember me" token
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = FirebaseUser::find($identifier);
        
        if ($user && $user->getRememberToken() === $token) {
            return $user;
        }
        
        return null;
    }

    /**
     * Mettre à jour le "remember me" token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    /**
     * Récupérer un utilisateur par ses identifiants
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials['email'])) {
            return null;
        }

        $user = FirebaseUser::whereEmail($credentials['email']);
        
        // Log pour débogage
        if (!$user) {
            \Log::warning('Utilisateur non trouvé pour email: ' . $credentials['email']);
        }
        
        return $user;
    }

    /**
     * Valider un utilisateur contre les identifiants fournis
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!$user) {
            \Log::warning('Tentative de validation avec un utilisateur null');
            return false;
        }
        
        $plain = $credentials['password'] ?? '';
        $hashedPassword = $user->getAuthPassword();
        
        if (empty($hashedPassword)) {
            \Log::warning('Mot de passe hashé vide pour l\'utilisateur: ' . ($user->email ?? 'unknown'));
            return false;
        }

        $isValid = Hash::check($plain, $hashedPassword);
        
        // Log pour débogage
        if (!$isValid) {
            \Log::warning('Mot de passe invalide pour l\'utilisateur: ' . ($user->email ?? 'unknown'));
        }
        
        return $isValid;
    }
}

