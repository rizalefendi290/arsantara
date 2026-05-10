<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ([
            ['name' => 'Rumah', 'slug' => 'rumah'],
            ['name' => 'Tanah', 'slug' => 'tanah'],
            ['name' => 'Mobil', 'slug' => 'mobil'],
            ['name' => 'Motor', 'slug' => 'motor'],
            ['name' => 'Ruko', 'slug' => 'ruko'],
            ['name' => 'Perkantoran', 'slug' => 'perkantoran'],
            ['name' => 'Gudang', 'slug' => 'gudang'],
            ['name' => 'Kios', 'slug' => 'kios'],
            ['name' => 'Truk & Kendaraan Komersil', 'slug' => 'truk-kendaraan-komersil'],
        ] as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true]
            );
        }
    }
}
