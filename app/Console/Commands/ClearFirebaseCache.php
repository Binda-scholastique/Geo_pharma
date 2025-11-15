<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseService;

class ClearFirebaseCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:clear-cache 
                            {--collection= : Vider le cache d\'une collection spécifique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vider le cache Firebase';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $firebase = new FirebaseService();
        $collection = $this->option('collection');
        
        if ($collection) {
            $this->info("Vidage du cache pour la collection: {$collection}");
            $firebase->clearCache($collection);
            $this->info("✅ Cache vidé avec succès !");
        } else {
            $this->info("Vidage de tous les caches Firebase...");
            $firebase->clearAllCache();
            $this->info("✅ Tous les caches ont été vidés avec succès !");
        }
        
        return 0;
    }
}

