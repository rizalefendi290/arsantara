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

            if (!Schema::hasColumn('car_details', 'fuel_type')) {
                $table->string('fuel_type')->nullable();
            }

            if (!Schema::hasColumn('car_details', 'color')) {
                $table->string('color')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_details', function (Blueprint $table) {
            $table->dropColumn(['fuel_type','color']);
        });
    }
};
