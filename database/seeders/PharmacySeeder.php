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
            'name' => 'Jedidia Umba',
            'email' => 'jedidia.umba@geopharma.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'profile_completed' => true,
            'city' => 'Kinshasa',
            'address' => 'Avenue Kasa-Vubu, Gombe',
            'postal_code' => '001',
        ]);

        // Créer plusieurs pharmaciens pour Kinshasa
        $pharmacist1 = User::create([
            'name' => 'Dr. Joviette Kandolo',
            'email' => 'joviette.kandolo@pharmacie.cd',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'authorization_number' => 'PH001234567',
            'profile_completed' => true,
            'city' => 'Kinshasa',
            'address' => 'Avenue Kasa-Vubu, Gombe',
            'postal_code' => '001',
            'latitude' => -4.3276,
            'longitude' => 15.3136,
        ]);

        $pharmacist2 = User::create([
            'name' => 'Dr. Binda Scholastique',
            'email' => 'binda.scholastique@geopharma.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'authorization_number' => 'PH002345678',
            'profile_completed' => true,
            'city' => 'Kinshasa',
            'address' => 'Avenue de la Justice, Matonge',
            'postal_code' => '002',
            'latitude' => -4.3500,
            'longitude' => 15.3000,
        ]);

        $pharmacist3 = User::create([
            'name' => 'Dr. Gothie',
            'email' => 'gothie@geopharma.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
            'authorization_number' => 'PH003456789',
            'profile_completed' => true,
            'city' => 'Kinshasa',
            'address' => 'Boulevard du 30 Juin, Bandal',
            'postal_code' => '003',
            'latitude' => -4.3100,
            'longitude' => 15.2800,
        ]);

        // Créer des numéros d'autorisation
        AuthorizationNumber::create([
            'number' => 'PH001234567',
            'is_valid' => true,
            'pharmacist_name' => 'Dr. Joviette Kandolo',
            'expires_at' => now()->addYear(),
        ]);

        AuthorizationNumber::create([
            'number' => 'PH002345678',
            'is_valid' => true,
            'pharmacist_name' => 'Dr. Binda Scholastique',
            'expires_at' => now()->addYear(),
        ]);

        AuthorizationNumber::create([
            'number' => 'PH003456789',
            'is_valid' => true,
            'pharmacist_name' => 'Dr. Gothie',
            'expires_at' => now()->addYear(),
        ]);

        // Créer des pharmacies de Kinshasa, RDC
        $pharmacies = [
            [
                'name' => 'Pharmacie du Centre-Ville',
                'description' => 'Pharmacie moderne au cœur de Gombe, spécialisée en conseil pharmaceutique et médicaments essentiels.',
                'address' => 'Avenue Kasa-Vubu, Gombe',
                'city' => 'Kinshasa',
                'postal_code' => '001',
                'country' => 'RD Congo',
                'latitude' => -4.3276,
                'longitude' => 15.3136,
                'phone' => '+243 999 123 456',
                'email' => 'contact@pharmacie-centre-ville.cd',
                'whatsapp_number' => '+243999123456',
                'opening_hours' => [
                    'lundi' => '07:00 - 20:00',
                    'mardi' => '07:00 - 20:00',
                    'mercredi' => '07:00 - 20:00',
                    'jeudi' => '07:00 - 20:00',
                    'vendredi' => '07:00 - 20:00',
                    'samedi' => '08:00 - 19:00',
                    'dimanche' => '09:00 - 18:00'
                ],
                'services' => [
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Médicaments essentiels',
                    'Vaccination',
                    'Mesure tension',
                    'Test de glycémie'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist1->id,
            ],
            [
                'name' => 'Pharmacie Matonge',
                'description' => 'Pharmacie de quartier à Matonge, ouverte 7j/7 pour vos besoins urgents. Service personnalisé et conseils adaptés.',
                'address' => 'Avenue de la Justice, Matonge',
                'city' => 'Kinshasa',
                'postal_code' => '002',
                'country' => 'RD Congo',
                'latitude' => -4.3500,
                'longitude' => 15.3000,
                'phone' => '+243 999 234 567',
                'email' => 'info@pharmacie-matonge.cd',
                'whatsapp_number' => '+243999234567',
                'opening_hours' => [
                    'lundi' => '07:00 - 21:00',
                    'mardi' => '07:00 - 21:00',
                    'mercredi' => '07:00 - 21:00',
                    'jeudi' => '07:00 - 21:00',
                    'vendredi' => '07:00 - 21:00',
                    'samedi' => '07:00 - 21:00',
                    'dimanche' => '08:00 - 20:00'
                ],
                'services' => [
                    'Pharmacie de garde',
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Médicaments génériques',
                    'Vaccination'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist2->id,
            ],
            [
                'name' => 'Pharmacie Bandal',
                'description' => 'Votre pharmacie de confiance à Bandal. Spécialisée en pharmacie clinique et conseils personnalisés pour toute la famille.',
                'address' => 'Boulevard du 30 Juin, Bandal',
                'city' => 'Kinshasa',
                'postal_code' => '003',
                'country' => 'RD Congo',
                'latitude' => -4.3100,
                'longitude' => 15.2800,
                'phone' => '+243 999 345 678',
                'email' => 'contact@pharmacie-bandal.cd',
                'whatsapp_number' => '+243999345678',
                'opening_hours' => [
                    'lundi' => '08:00 - 19:00',
                    'mardi' => '08:00 - 19:00',
                    'mercredi' => '08:00 - 19:00',
                    'jeudi' => '08:00 - 19:00',
                    'vendredi' => '08:00 - 19:00',
                    'samedi' => '08:00 - 18:00',
                    'dimanche' => '09:00 - 17:00'
                ],
                'services' => [
                    'Conseil pharmaceutique',
                    'Médicaments essentiels',
                    'Vaccination',
                    'Mesure tension',
                    'Test de glycémie',
                    'Conseil nutritionnel'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist3->id,
            ],
            [
                'name' => 'Pharmacie Ngaliema',
                'description' => 'Pharmacie moderne dans le quartier résidentiel de Ngaliema. Équipée des dernières technologies pour un service optimal.',
                'address' => 'Avenue Colonel Lukusa, Ngaliema',
                'city' => 'Kinshasa',
                'postal_code' => '004',
                'country' => 'RD Congo',
                'latitude' => -4.3400,
                'longitude' => 15.2500,
                'phone' => '+243 999 456 789',
                'email' => 'info@pharmacie-ngaliema.cd',
                'whatsapp_number' => '+243999456789',
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
                    'Médicaments importés',
                    'Vaccination',
                    'Mesure tension',
                    'Pharmacie de garde',
                    'Préparation magistrale'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist1->id,
            ],
            [
                'name' => 'Pharmacie Limete',
                'description' => 'Pharmacie familiale à Limete, spécialisée en médicaments essentiels et conseils pharmaceutiques pour toute la famille.',
                'address' => 'Avenue Lumumba, Limete',
                'city' => 'Kinshasa',
                'postal_code' => '005',
                'country' => 'RD Congo',
                'latitude' => -4.3800,
                'longitude' => 15.3200,
                'phone' => '+243 999 567 890',
                'email' => 'contact@pharmacie-limete.cd',
                'whatsapp_number' => '+243999567890',
                'opening_hours' => [
                    'lundi' => '07:30 - 19:30',
                    'mardi' => '07:30 - 19:30',
                    'mercredi' => '07:30 - 19:30',
                    'jeudi' => '07:30 - 19:30',
                    'vendredi' => '07:30 - 19:30',
                    'samedi' => '08:00 - 18:00',
                    'dimanche' => 'Fermé'
                ],
                'services' => [
                    'Conseil pharmaceutique',
                    'Médicaments essentiels',
                    'Médicaments génériques',
                    'Vaccination',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => false, // En attente de vérification
                'pharmacist_id' => $pharmacist2->id,
            ],
            [
                'name' => 'Pharmacie Binza',
                'description' => 'Votre pharmacie de quartier à Binza. Service personnalisé et conseils adaptés pour votre santé et celle de votre famille.',
                'address' => 'Avenue de la Paix, Binza',
                'city' => 'Kinshasa',
                'postal_code' => '006',
                'country' => 'RD Congo',
                'latitude' => -4.3000,
                'longitude' => 15.2700,
                'phone' => '+243 999 678 901',
                'email' => 'info@pharmacie-binza.cd',
                'whatsapp_number' => '+243999678901',
                'opening_hours' => [
                    'lundi' => '08:00 - 19:00',
                    'mardi' => '08:00 - 19:00',
                    'mercredi' => '08:00 - 19:00',
                    'jeudi' => '08:00 - 19:00',
                    'vendredi' => '08:00 - 19:00',
                    'samedi' => '08:00 - 18:00',
                    'dimanche' => '09:00 - 16:00'
                ],
                'services' => [
                    'Conseil pharmaceutique',
                    'Médicaments essentiels',
                    'Vaccination',
                    'Mesure tension',
                    'Test de glycémie'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist3->id,
            ],
            [
                'name' => 'Pharmacie Kintambo',
                'description' => 'Pharmacie moderne à Kintambo, ouverte 7j/7. Spécialisée en pharmacie clinique et médicaments de qualité.',
                'address' => 'Avenue Kasa-Vubu, Kintambo',
                'city' => 'Kinshasa',
                'postal_code' => '007',
                'country' => 'RD Congo',
                'latitude' => -4.3200,
                'longitude' => 15.2900,
                'phone' => '+243 999 789 012',
                'email' => 'contact@pharmacie-kintambo.cd',
                'whatsapp_number' => '+243999789012',
                'opening_hours' => [
                    'lundi' => '07:00 - 21:00',
                    'mardi' => '07:00 - 21:00',
                    'mercredi' => '07:00 - 21:00',
                    'jeudi' => '07:00 - 21:00',
                    'vendredi' => '07:00 - 21:00',
                    'samedi' => '07:00 - 21:00',
                    'dimanche' => '08:00 - 20:00'
                ],
                'services' => [
                    'Pharmacie de garde',
                    'Livraison à domicile',
                    'Conseil pharmaceutique',
                    'Médicaments importés',
                    'Vaccination',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist1->id,
            ],
            [
                'name' => 'Pharmacie Masina',
                'description' => 'Pharmacie de quartier à Masina. Votre partenaire santé avec des conseils personnalisés et des médicaments essentiels.',
                'address' => 'Avenue Masina, Masina',
                'city' => 'Kinshasa',
                'postal_code' => '008',
                'country' => 'RD Congo',
                'latitude' => -4.4000,
                'longitude' => 15.3500,
                'phone' => '+243 999 890 123',
                'email' => 'info@pharmacie-masina.cd',
                'whatsapp_number' => '+243999890123',
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
                    'Médicaments essentiels',
                    'Médicaments génériques',
                    'Vaccination',
                    'Mesure tension'
                ],
                'is_active' => true,
                'is_verified' => true,
                'pharmacist_id' => $pharmacist2->id,
            ]
        ];

        foreach ($pharmacies as $pharmacyData) {
            Pharmacy::create($pharmacyData);
        }

        $this->command->info('✅ Données de test créées avec succès !');
        $this->command->info('📍 Localisation: Kinshasa, RD Congo');
        $this->command->info('👤 Utilisateur: jedidia.umba@geopharma.com / password');
        $this->command->info('👨‍⚕️ Pharmacien 1: joviette.kandolo@geopharma.com / password');
        $this->command->info('👨‍⚕️ Pharmacien 2: binda.scholastique@geopharma.com / password');
        $this->command->info('👨‍⚕️ Pharmacien 3: gothie@geopharma.com / password');
        $this->command->info('🏥 ' . count($pharmacies) . ' pharmacies créées dans différents quartiers de Kinshasa');
    }
}