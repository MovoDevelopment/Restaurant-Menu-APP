<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create root categories
        $rootCategories = Category::factory()->count(5)->create();

        // Create children for each root category
        foreach ($rootCategories as $rootCategory) {
            $this->createChildren($rootCategory, 1);
        }
    }

    private function createChildren(Category $parent, $depth)
    {
        if ($depth >= 4) {
            // Create the last subcategory and mark it as last_child
            $lastChild = Category::factory()->create([
                'parent_id' => $parent->id,
                'path' => $parent->path . $parent->id,
                'last_child' => true,
            ]);

            return;
        }

        $children = Category::factory()->count(3)->create([
            'parent_id' => $parent->id,
            'path' => $parent->path . $parent->id . ',',
        ]);

        foreach ($children as $child) {
            $this->createChildren($child, $depth + 1);
        }
    }
}
