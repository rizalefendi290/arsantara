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
        Category::create(['name'=>'Rumah','slug'=>'rumah']);
        Category::create(['name'=>'Tanah','slug'=>'tanah']);
        Category::create(['name'=>'Mobil','slug'=>'mobil']);
        Category::create(['name'=>'Motor','slug'=>'motor']);
    }
}
