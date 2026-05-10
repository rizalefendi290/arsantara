<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('categories')->updateOrInsert(
            ['slug' => 'truk-kendaraan-komersil'],
            [
                'name' => 'Truk & Kendaraan Komersil',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('categories')
            ->where('slug', 'truk-kendaraan-komersil')
            ->delete();
    }
};
