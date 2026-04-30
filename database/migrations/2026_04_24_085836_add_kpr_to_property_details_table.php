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
        if (!Schema::hasColumn('property_details', 'is_kpr')) {
            Schema::table('property_details', function (Blueprint $table) {
                $table->boolean('is_kpr')->default(false)->after('certificate');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_details', function (Blueprint $table) {
            $table->dropColumn('is_kpr');
        });
    }
};
