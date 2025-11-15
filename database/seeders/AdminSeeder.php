<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FirebaseUser;
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
        // Vérifier si l'admin existe déjà
        $existingAdmin = FirebaseUser::all()->where('email', 'admin@geopharma.com')->first();
        
        if ($existingAdmin) {
            $this->command->warn('Un administrateur avec cet email existe déjà.');
            return;
        }
        
        // Créer un utilisateur administrateur
        $admin = new FirebaseUser([
            'name' => 'Administrateur',
            'email' => 'admin@geopharma.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);
        $admin->save();

        $this->command->info('✅ Utilisateur administrateur créé avec succès !');
        $this->command->info('📧 Email: admin@geopharma.com');
        $this->command->info('🔑 Mot de passe: password');
        $this->command->warn('⚠️  Veuillez changer le mot de passe après la première connexion !');
    }
}