<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Hash;

class TestPharmaciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un pharmacien de test s'il n'existe pas
        $pharmacist = User::firstOrCreate(
            ['email' => 'pharmacien.test@example.com'],
            [
                'name' => 'Dr. Marie Martin',
                'password' => Hash::make('password'),
                'role' => 'pharmacist',
                'authorization_number' => 'PHARM123456',
                'profile_completed' => true,
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'address' => '123 Rue de la Paix',
                'city' => 'Paris',
                'postal_code' => '75001',
            ]
        );

        // Créer des pharmacies de test pour ce pharmacien
        $pharmacies = [
            [
                'name' => 'Pharmacie Centrale',
                'description' => 'Pharmacie de garde 24h/24 avec service d\'urgence',
                'address' => '15 Avenue des Champs-Élysées',
                'city' => 'Paris',
                'postal_code' => '75008',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'phone' => '01 42 86 83 26',
                'email' => 'contact@pharmacie-centrale.fr',
                'whatsapp_number' => '+33142868326',
                'opening_hours' => [
                    'lundi' => '08:00-20:00',
                    'mardi' => '08:00-20:00',
                    'mercredi' => '08:00-20:00',
                    'jeudi' => '08:00-20:00',
                    'vendredi' => '08:00-20:00',
                    'samedi' => '08:00-18:00',
                    'dimanche' => '09:00-13:00'
                ],
                'services' => ['delivery', 'emergency', 'vaccination', 'consultation'],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie du Marais',
                'description' => 'Pharmacie spécialisée en médecine naturelle',
                'address' => '8 Rue des Rosiers',
                'city' => 'Paris',
                'postal_code' => '75004',
                'country' => 'France',
                'latitude' => 48.8575,
                'longitude' => 2.3584,
                'phone' => '01 42 77 66 55',
                'email' => 'info@pharmacie-marais.fr',
                'whatsapp_number' => '+33142776655',
                'opening_hours' => [
                    'lundi' => '09:00-19:00',
                    'mardi' => '09:00-19:00',
                    'mercredi' => '09:00-19:00',
                    'jeudi' => '09:00-19:00',
                    'vendredi' => '09:00-19:00',
                    'samedi' => '09:00-17:00',
                    'dimanche' => 'Fermé'
                ],
                'services' => ['delivery', 'consultation', 'natural_medicine'],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie de la Gare',
                'description' => 'Pharmacie ouverte tard le soir pour les voyageurs',
                'address' => '45 Boulevard de la Villette',
                'city' => 'Paris',
                'postal_code' => '75019',
                'country' => 'France',
                'latitude' => 48.8806,
                'longitude' => 2.3556,
                'phone' => '01 40 40 90 90',
                'email' => 'contact@pharmacie-gare.fr',
                'whatsapp_number' => '+33140409090',
                'opening_hours' => [
                    'lundi' => '07:00-23:00',
                    'mardi' => '07:00-23:00',
                    'mercredi' => '07:00-23:00',
                    'jeudi' => '07:00-23:00',
                    'vendredi' => '07:00-23:00',
                    'samedi' => '08:00-22:00',
                    'dimanche' => '08:00-20:00'
                ],
                'services' => ['delivery', 'emergency', 'travel_medicine'],
                'is_active' => true,
                'is_verified' => false, // En attente de vérification
                'pharmacist_id' => $pharmacist->id,
            ]
        ];

        foreach ($pharmacies as $pharmacyData) {
            Pharmacy::firstOrCreate(
                [
                    'name' => $pharmacyData['name'],
                    'pharmacist_id' => $pharmacist->id
                ],
                $pharmacyData
            );
        }

        $this->command->info('Pharmacies de test créées avec succès pour le pharmacien: ' . $pharmacist->name);
    }
}