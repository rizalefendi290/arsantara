<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('car_details', function (Blueprint $table) {
            $table->enum('fuel_type', ['bensin', 'diesel', 'listrik', 'hybrid'])
                  ->after('engine')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_details', function (Blueprint $table) {
            $table->dropColumn('fuel_type');
        });
    }
};
