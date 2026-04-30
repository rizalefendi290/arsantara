<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('listings', 'discount_price')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->bigInteger('discount_price')->nullable()->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('listings', 'discount_price')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('discount_price');
            });
        }
    }
};
