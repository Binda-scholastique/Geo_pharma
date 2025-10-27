<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            // Informations de base de la pharmacie
            $table->string('name'); // Nom de la pharmacie
            $table->text('description')->nullable(); // Description de la pharmacie
            $table->string('address'); // Adresse complète
            $table->string('city'); // Ville
            $table->string('postal_code'); // Code postal
            $table->string('country')->default('France'); // Pays
            
            // Coordonnées géographiques pour la géolocalisation
            $table->decimal('latitude', 10, 8); // Latitude
            $table->decimal('longitude', 11, 8); // Longitude
            
            // Informations de contact
            $table->string('phone'); // Numéro de téléphone
            $table->string('email')->nullable(); // Email de contact
            $table->string('whatsapp_number')->nullable(); // Numéro WhatsApp
            
            // Horaires d'ouverture (JSON pour stocker les horaires par jour)
            $table->json('opening_hours')->nullable();
            
            // Services proposés
            $table->json('services')->nullable(); // Services disponibles (livraison, garde, etc.)
            
            // Statut et validation
            $table->boolean('is_active')->default(true); // Pharmacie active ou non
            $table->boolean('is_verified')->default(false); // Vérifiée par l'admin
            
            // Relation avec le pharmacien propriétaire
            $table->foreignId('pharmacist_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Index pour optimiser les recherches géographiques
            $table->index(['latitude', 'longitude']);
            $table->index(['city', 'postal_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pharmacies');
    }
}
