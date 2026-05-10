<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            ['name' => 'Ruko', 'slug' => 'ruko'],
            ['name' => 'Perkantoran', 'slug' => 'perkantoran'],
            ['name' => 'Gudang', 'slug' => 'gudang'],
            ['name' => 'Kios', 'slug' => 'kios'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('slug', ['ruko', 'perkantoran', 'gudang', 'kios'])
            ->whereNotIn('id', DB::table('listings')->select('category_id'))
            ->delete();
    }
};
