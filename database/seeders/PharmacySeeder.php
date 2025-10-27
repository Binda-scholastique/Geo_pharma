<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\AuthorizationNumber;
use Illuminate\Support\Facades\Hash;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un utilisateur normal
        $user = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'profile_completed' => true,
        ]);

        // Créer un pharmacien
        $pharmacist = User::create([
            'name' => 'Dr. Marie Martin',
            'email' => 'marie.martin@pharmacie.fr',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'authorization_number' => 'PH123456789',
            'profile_completed' => true,
        ]);

        // Créer un numéro d'autorisation
        AuthorizationNumber::create([
            'number' => 'PH123456789',
            'is_valid' => true,
            'pharmacist_name' => 'Dr. Marie Martin',
            'pharmacy_name' => 'Pharmacie du Centre',
            'expires_at' => now()->addYear(),
        ]);

        // Créer des pharmacies de test
        $pharmacies = [
            [
                'name' => 'Pharmacie du Centre',
                'description' => 'Pharmacie familiale au cœur de la ville, spécialisée en conseil pharmaceutique et préparation magistrale.',
                'address' => '15 Place de la République',
                'city' => 'Paris',
                'postal_code' => '75001',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'phone' => '01 23 45 67 89',
                'email' => 'contact@pharmacie-centre.fr',
                'whatsapp_number' => '+33123456789',
                'opening_hours' => [
                    'lundi' => '08:00 - 20:00',
                    'mardi' => '08:00 - 20:00',
                    'mercredi' => '08:00 - 20:00',
                    'jeudi' => '08:00 - 20:00',
                    'vendredi' => '08:00 - 20:00',
                    'samedi' => '08:00 - 19:00',
                    'dimanche' => '09:00 - 18:00'
                ],
                'services' => [
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Préparation magistrale',
                    'Vaccination',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie de la Gare',
                'description' => 'Pharmacie ouverte 7j/7, idéalement située près de la gare pour vos besoins urgents.',
                'address' => '42 Avenue de la Gare',
                'city' => 'Lyon',
                'postal_code' => '69001',
                'country' => 'France',
                'latitude' => 45.7640,
                'longitude' => 4.8357,
                'phone' => '04 78 12 34 56',
                'email' => 'info@pharmacie-gare.fr',
                'whatsapp_number' => '+33478123456',
                'opening_hours' => [
                    'lundi' => '07:00 - 22:00',
                    'mardi' => '07:00 - 22:00',
                    'mercredi' => '07:00 - 22:00',
                    'jeudi' => '07:00 - 22:00',
                    'vendredi' => '07:00 - 22:00',
                    'samedi' => '07:00 - 22:00',
                    'dimanche' => '08:00 - 21:00'
                ],
                'services' => [
                    'Pharmacie de garde',
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Vaccination'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie du Quartier',
                'description' => 'Votre pharmacie de quartier avec un service personnalisé et des conseils adaptés.',
                'address' => '8 Rue des Lilas',
                'city' => 'Marseille',
                'postal_code' => '13001',
                'country' => 'France',
                'latitude' => 43.2965,
                'longitude' => 5.3698,
                'phone' => '04 91 23 45 67',
                'email' => 'contact@pharmacie-quartier.fr',
                'whatsapp_number' => '+33491234567',
                'opening_hours' => [
                    'lundi' => '08:30 - 19:30',
                    'mardi' => '08:30 - 19:30',
                    'mercredi' => '08:30 - 19:30',
                    'jeudi' => '08:30 - 19:30',
                    'vendredi' => '08:30 - 19:30',
                    'samedi' => '08:30 - 18:00',
                    'dimanche' => 'Fermé'
                ],
                'services' => [
                    'Conseil pharmaceutique',
                    'Préparation magistrale',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => false, // En attente de vérification
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie Moderne',
                'description' => 'Pharmacie équipée des dernières technologies pour un service optimal.',
                'address' => '25 Boulevard des Champs-Élysées',
                'city' => 'Nice',
                'postal_code' => '06000',
                'country' => 'France',
                'latitude' => 43.7102,
                'longitude' => 7.2620,
                'phone' => '04 93 12 34 56',
                'email' => 'info@pharmacie-moderne.fr',
                'whatsapp_number' => '+33493123456',
                'opening_hours' => [
                    'lundi' => '08:00 - 20:00',
                    'mardi' => '08:00 - 20:00',
                    'mercredi' => '08:00 - 20:00',
                    'jeudi' => '08:00 - 20:00',
                    'vendredi' => '08:00 - 20:00',
                    'samedi' => '08:00 - 19:00',
                    'dimanche' => '10:00 - 18:00'
                ],
                'services' => [
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Vaccination',
                    'Mesure tension',
                    'Pharmacie de garde'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ],
            [
                'name' => 'Pharmacie Santé Plus',
                'description' => 'Spécialisée en pharmacie clinique et conseils personnalisés.',
                'address' => '12 Rue de la Santé',
                'city' => 'Toulouse',
                'postal_code' => '31000',
                'country' => 'France',
                'latitude' => 43.6047,
                'longitude' => 1.4442,
                'phone' => '05 61 23 45 67',
                'email' => 'contact@sante-plus.fr',
                'whatsapp_number' => '+33561234567',
                'opening_hours' => [
                    'lundi' => '08:00 - 19:00',
                    'mardi' => '08:00 - 19:00',
                    'mercredi' => '08:00 - 19:00',
                    'jeudi' => '08:00 - 19:00',
                    'vendredi' => '08:00 - 19:00',
                    'samedi' => '08:00 - 18:00',
                    'dimanche' => 'Fermé'
                ],
                'services' => [
                    'Conseil pharmaceutique',
                    'Préparation magistrale',
                    'Vaccination',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist->id,
            ]
        ];

        foreach ($pharmacies as $pharmacyData) {
            Pharmacy::create($pharmacyData);
        }

        $this->command->info('Données de test créées avec succès !');
        $this->command->info('Utilisateur: jean.dupont@example.com / password');
        $this->command->info('Pharmacien: marie.martin@pharmacie.fr / password');
    }
}