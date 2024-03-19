<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function update(UpdateCategoryRequest $updateCategoryRequest)
    {
        $category = Category::find($updateCategoryRequest->id);
        $category->name = $updateCategoryRequest->name;
        if ($updateCategoryRequest->parent_id) {
            $path = $category->child;
            array_pop($path);
            $category->parent_id = $updateCategoryRequest->parent_id;
            $category->path = implode(',', $path) . "," . $updateCategoryRequest->parent_id;
        }
        if (Auth::user()->role != "admin")
            $category->user_id = Auth::user()->id;
        else
            $category->user_id = $updateCategoryRequest->user_id;
        $category->save();
        return $this->sendResponse(new CategoryResource($category), "Category updated");
    }

    public function destroy(DeleteCategoryRequest $deleteCategoryRequest)
    {
        $category = Category::find($deleteCategoryRequest->id);
    }

    public function leafNodes()
    {
        $nodes = DB::table('categories as t1')
            ->select('t1.id', 't1.name')
            ->leftJoin('categories as t2', 't1.id', '=', 't2.parent_id')
            ->whereNull('t2.parent_id')
            ->get();
        return $this->sendResponse($nodes, "Category updated");

    }
}
