<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{

    public function storeCategory($name, $lastChild, $path, $parentId = null): Category
    {
        $category = new Category();
        $category->name = $name;
        $category->last_child = $lastChild;
        $category->parent_id = $parentId;
        $category->path = $path;
        $category->save();
        return $category;
    }

}
