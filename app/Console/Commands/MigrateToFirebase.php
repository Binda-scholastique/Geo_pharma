<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\AuthorizationNumber;
use Illuminate\Support\Facades\DB;

class MigrateToFirebase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:migrate 
                            {--dry-run : Exécuter sans écrire dans Firebase}
                            {--collection= : Migrer une collection spécifique (users, pharmacies, authorization_numbers)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrer les données MySQL vers Firebase Firestore';

    protected $firebase;
    protected $dryRun = false;
    protected $stats = [
        'users' => ['total' => 0, 'migrated' => 0, 'errors' => 0],
        'pharmacies' => ['total' => 0, 'migrated' => 0, 'errors' => 0],
        'authorization_numbers' => ['total' => 0, 'migrated' => 0, 'errors' => 0],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->dryRun = $this->option('dry-run');
        $collection = $this->option('collection');

        $this->info('🔥 Migration vers Firebase Firestore');
        $this->info('=====================================');
        
        if ($this->dryRun) {
            $this->warn('⚠️  Mode DRY-RUN activé - Aucune donnée ne sera écrite');
        }

        try {
            // Initialiser Firebase
            $this->info('Initialisation de Firebase...');
            $this->firebase = new FirebaseService();
            
            if (!$this->firebase->testConnection()) {
                $this->error('❌ Impossible de se connecter à Firebase');
                return 1;
            }
            $this->info('✅ Connexion Firebase réussie');

            // Migrer selon la collection spécifiée ou toutes
            if ($collection) {
                $this->migrateCollection($collection);
            } else {
                $this->migrateCollection('users');
                $this->migrateCollection('authorization_numbers');
                $this->migrateCollection('pharmacies');
            }

            // Afficher les statistiques
            $this->displayStats();

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erreur : ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    protected function migrateCollection(string $collectionName)
    {
        $this->info("\n📦 Migration de la collection : {$collectionName}");

        switch ($collectionName) {
            case 'users':
                $this->migrateUsers();
                break;
            case 'pharmacies':
                $this->migratePharmacies();
                break;
            case 'authorization_numbers':
                $this->migrateAuthorizationNumbers();
                break;
            default:
                $this->error("Collection inconnue : {$collectionName}");
                return;
        }
    }

    protected function migrateUsers()
    {
        $users = User::all();
        $this->stats['users']['total'] = $users->count();
        
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            try {
                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password, // Hash conservé
                    'role' => $user->role,
                    'authorization_number' => $user->authorization_number,
                    'profile_completed' => $user->profile_completed ?? false,
                    'latitude' => $user->latitude ? (float) $user->latitude : null,
                    'longitude' => $user->longitude ? (float) $user->longitude : null,
                    'address' => $user->address,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    'created_at' => $user->created_at->toIso8601String(),
                    'updated_at' => $user->updated_at->toIso8601String(),
                ];

                if (!$this->dryRun) {
                    $this->firebase->create('users', $data, (string) $user->id);
                }
                
                $this->stats['users']['migrated']++;
            } catch (\Exception $e) {
                $this->stats['users']['errors']++;
                $this->error("\nErreur pour l'utilisateur {$user->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Utilisateurs migrés : {$this->stats['users']['migrated']}/{$this->stats['users']['total']}");
    }

    protected function migratePharmacies()
    {
        $pharmacies = Pharmacy::all();
        $this->stats['pharmacies']['total'] = $pharmacies->count();
        
        $bar = $this->output->createProgressBar($pharmacies->count());
        $bar->start();

        foreach ($pharmacies as $pharmacy) {
            try {
                $data = [
                    'name' => $pharmacy->name,
                    'description' => $pharmacy->description,
                    'address' => $pharmacy->address,
                    'city' => $pharmacy->city,
                    'postal_code' => $pharmacy->postal_code,
                    'country' => $pharmacy->country,
                    'latitude' => $pharmacy->latitude ? (float) $pharmacy->latitude : null,
                    'longitude' => $pharmacy->longitude ? (float) $pharmacy->longitude : null,
                    'phone' => $pharmacy->phone,
                    'email' => $pharmacy->email,
                    'whatsapp_number' => $pharmacy->whatsapp_number,
                    'opening_hours' => $pharmacy->opening_hours ?? [],
                    'services' => $pharmacy->services ?? [],
                    'is_active' => $pharmacy->is_active ?? true,
                    'is_verified' => $pharmacy->is_verified ?? false,
                    'pharmacist_id' => $pharmacy->pharmacist_id ? (string) $pharmacy->pharmacist_id : null,
                    'created_at' => $pharmacy->created_at->toIso8601String(),
                    'updated_at' => $pharmacy->updated_at->toIso8601String(),
                ];

                if (!$this->dryRun) {
                    $this->firebase->create('pharmacies', $data, (string) $pharmacy->id);
                }
                
                $this->stats['pharmacies']['migrated']++;
            } catch (\Exception $e) {
                $this->stats['pharmacies']['errors']++;
                $this->error("\nErreur pour la pharmacie {$pharmacy->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Pharmacies migrées : {$this->stats['pharmacies']['migrated']}/{$this->stats['pharmacies']['total']}");
    }

    protected function migrateAuthorizationNumbers()
    {
        $authorizations = AuthorizationNumber::all();
        $this->stats['authorization_numbers']['total'] = $authorizations->count();
        
        $bar = $this->output->createProgressBar($authorizations->count());
        $bar->start();

        foreach ($authorizations as $auth) {
            try {
                $data = [
                    'number' => $auth->number,
                    'is_valid' => $auth->is_valid ?? true,
                    'expires_at' => $auth->expires_at ? $auth->expires_at->toIso8601String() : null,
                    'pharmacist_name' => $auth->pharmacist_name,
                    'pharmacy_name' => $auth->pharmacy_name,
                    'created_at' => $auth->created_at->toIso8601String(),
                    'updated_at' => $auth->updated_at->toIso8601String(),
                ];

                if (!$this->dryRun) {
                    $this->firebase->create('authorization_numbers', $data, (string) $auth->id);
                }
                
                $this->stats['authorization_numbers']['migrated']++;
            } catch (\Exception $e) {
                $this->stats['authorization_numbers']['errors']++;
                $this->error("\nErreur pour l'autorisation {$auth->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Numéros d'autorisation migrés : {$this->stats['authorization_numbers']['migrated']}/{$this->stats['authorization_numbers']['total']}");
    }

    protected function displayStats()
    {
        $this->info("\n\n📊 Statistiques de migration");
        $this->info("=============================");
        
        foreach ($this->stats as $collection => $stat) {
            $this->info("\n{$collection}:");
            $this->info("  Total : {$stat['total']}");
            $this->info("  Migrés : {$stat['migrated']}");
            if ($stat['errors'] > 0) {
                $this->warn("  Erreurs : {$stat['errors']}");
            }
        }
    }
}
