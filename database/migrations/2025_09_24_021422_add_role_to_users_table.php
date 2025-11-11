<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter le champ role pour distinguer les utilisateurs et pharmaciens
            $table->enum('role', ['user', 'pharmacist', 'admin'])->after('email');
            // Ajouter le numéro d'autorisation pour les pharmaciens
            $table->string('authorization_number')->nullable()->after('role');
            // Ajouter le statut de vérification du profil
            $table->boolean('profile_completed')->default(false)->after('authorization_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn(['role', 'authorization_number', 'profile_completed']);
        });
    }
}
