<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un utilisateur administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@geopharma.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'profile_completed' => true,
        ]);

        $this->command->info('Utilisateur administrateur créé avec succès !');
        $this->command->info('Email: admin@geopharma.com');
        $this->command->info('Mot de passe: password');
    }
}