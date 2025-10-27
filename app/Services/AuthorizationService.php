<?php

namespace App\Services;

use App\Models\AuthorizationNumber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizationService
{
    /**
     * Vérifier un numéro d'autorisation via l'API externe
     * 
     * @param string $authorizationNumber
     * @return array
     */
    public function verifyAuthorizationNumber(string $authorizationNumber): array
    {
        try {
            // Pour l'instant, on simule une vérification
            // Plus tard, vous pourrez remplacer par un appel à votre API réelle
            
            // Simulation d'une vérification réussie
            $isValid = $this->simulateApiCall($authorizationNumber);
            
            if ($isValid) {
                return [
                    'valid' => true,
                    'message' => 'Numéro d\'autorisation valide',
                    'data' => [
                        'number' => $authorizationNumber,
                        'pharmacist_name' => 'Pharmacien Test',
                        'pharmacy_name' => 'Pharmacie Test',
                        'expires_at' => now()->addYear()->format('Y-m-d')
                    ]
                ];
            }
            
            return [
                'valid' => false,
                'message' => 'Numéro d\'autorisation invalide ou expiré',
                'data' => null
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du numéro d\'autorisation: ' . $e->getMessage());
            
            return [
                'valid' => false,
                'message' => 'Erreur lors de la vérification. Veuillez réessayer.',
                'data' => null
            ];
        }
    }
    
    /**
     * Simuler un appel API (à remplacer par l'API réelle)
     * 
     * @param string $authorizationNumber
     * @return bool
     */
    private function simulateApiCall(string $authorizationNumber): bool
    {
        // Simulation : les numéros commençant par "PH" sont valides
        return str_starts_with(strtoupper($authorizationNumber), 'PH');
    }
    
    /**
     * Vérifier un numéro d'autorisation en base de données
     * 
     * @param string $authorizationNumber
     * @return AuthorizationNumber|null
     */
    public function checkInDatabase(string $authorizationNumber): ?AuthorizationNumber
    {
        return AuthorizationNumber::where('number', $authorizationNumber)
            ->valid()
            ->notExpired()
            ->first();
    }
    
    /**
     * Créer ou mettre à jour un numéro d'autorisation en base
     * 
     * @param array $data
     * @return AuthorizationNumber
     */
    public function createOrUpdateAuthorization(array $data): AuthorizationNumber
    {
        return AuthorizationNumber::updateOrCreate(
            ['number' => $data['number']],
            $data
        );
    }
    
    /**
     * Méthode pour appeler l'API réelle (à implémenter plus tard)
     * 
     * @param string $authorizationNumber
     * @return array
     */
    private function callRealApi(string $authorizationNumber): array
    {
        // Exemple d'appel API avec Guzzle
        /*
        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.authorization_api.key'),
                'Content-Type' => 'application/json',
            ])
            ->post(config('services.authorization_api.url'), [
                'authorization_number' => $authorizationNumber
            ]);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('API call failed: ' . $response->body());
        */
        
        // Pour l'instant, retourner une simulation
        return $this->simulateApiCall($authorizationNumber) ? 
            ['valid' => true, 'data' => []] : 
            ['valid' => false, 'data' => null];
    }
}
