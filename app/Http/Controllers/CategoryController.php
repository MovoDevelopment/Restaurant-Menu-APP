<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = Category::query()->whereNull('parent_id')->get();
        return $this->sendResponse(CategoryResource::collection($categories), "Categories");
    }

    public function store(StoreCategoryRequest $storeCategoryRequest)
    {
        $isNewCategory = !$storeCategoryRequest->category_id;
        $category = $isNewCategory ? $this->categoryRepository->storeCategory($storeCategoryRequest->name, false, "") : Category::find($storeCategoryRequest->category_id);

        if ($isNewCategory || !$category->last_child) {
            $newPath = $category->path . ($category->path ? ',' : '') . $category->id;

            if ($category->child_count < 3) {
                $category = $this->categoryRepository->storeCategory($storeCategoryRequest->name, false, $newPath, $category->id);
            } elseif ($category->child_count == 3) {
                $this->categoryRepository->storeCategory($storeCategoryRequest->name, true, $newPath, $category->id);
            }

            return $this->sendResponse(new CategoryResource($category), "Category created");
        }
        return $this->sendError([], 400, "Cannot add new subcategory");
    }
}
