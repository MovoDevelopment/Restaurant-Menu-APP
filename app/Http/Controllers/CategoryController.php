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
        $query = Category::query();
        if (Auth::user()->role != "admin")
            $query->where('user_id', Auth::user()->id);
        $categories = $query->whereNull('parent_id')->get();
        return $this->sendResponse(CategoryResource::collection($categories), "Categories");
    }

    public function store(StoreCategoryRequest $storeCategoryRequest)
    {
        if (!$storeCategoryRequest->category_id) {
            $category = $this->categoryRepository->storeCategory($storeCategoryRequest->name, false, "");
            return $this->sendResponse(new CategoryResource($category), "Category created");
        }

        $category = Category::find($storeCategoryRequest->category_id);
        if ($category->last_child) {
            return $this->sendError([], 400, "Cannot add new subcategory");
        }

        $newPath = $category->path;
        if (!empty($newPath)) {
            $newPath .= ',';
        }
        $newPath .= $category->id;

        if ($category->child_count >= 0 && $category->child_count < 3) {
            $category = $this->categoryRepository->storeCategory($storeCategoryRequest->name, false, $newPath, $category->id);
            return $this->sendResponse(new CategoryResource($category), "Category created");
        }

        if ($category->child_count == 3) {
            $this->categoryRepository->storeCategory($storeCategoryRequest->name, true, $newPath, $category->id);
            return $this->sendResponse(new CategoryResource($category), "Category created");
        }

        return $this->sendResponse(new CategoryResource($category), "Category created");
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
        $category = Category::query()->where('id', $deleteCategoryRequest->id)->with('items')->first();
        if ($category->parent_id == null) {
            $category->items()->delete();
        } else {
            $parentId = $category->parent_id;
            $category->items()->update(['category_id' => $parentId]);
        }
        return $this->sendResponse(new CategoryResource($category), "Category updated");
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
