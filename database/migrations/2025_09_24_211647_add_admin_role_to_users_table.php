<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Supprimer la colonne role existante
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        // Recréer la colonne role avec 'admin'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'pharmacist', 'admin'])->default('user')->after('email');
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
            //
        });
    }
}
