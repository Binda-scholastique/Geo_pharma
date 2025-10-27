<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorizationNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authorization_numbers', function (Blueprint $table) {
            $table->id();
            // Numéro d'autorisation unique
            $table->string('number')->unique();
            // Statut de l'autorisation
            $table->boolean('is_valid')->default(true);
            // Date d'expiration (optionnelle)
            $table->date('expires_at')->nullable();
            // Informations supplémentaires
            $table->string('pharmacist_name')->nullable();
            $table->string('pharmacy_name')->nullable();
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('number');
            $table->index('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authorization_numbers');
    }
}
