<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Categoria', 'type' => 'Category','slug' => 'categoria'],
            ['name' => 'Subcategoria', 'type' => 'SubCategory','slug' => 'subcategoria'],
        ];

        foreach ($categories as $category) {
            Categories::create([
                'name' => $category['name'],
                'type' => $category['type'],
                'slug' => $category['slug']
            ]);
            
        }
    }
}
