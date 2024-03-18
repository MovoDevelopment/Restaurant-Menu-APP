<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->whereNull('parent_id')->get();
        return $this->sendResponse(CategoryResource::collection($categories), "Categories");
    }

    public function store(StoreCategoryRequest $storeCategoryRequest)
    {

    }
}
