<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('profile_completed');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('address')->nullable()->after('longitude');
            $table->string('city')->nullable()->after('address');
            $table->string('postal_code')->nullable()->after('city');
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
            $table->dropColumn(['latitude', 'longitude', 'address', 'city', 'postal_code']);
        });
    }
}
