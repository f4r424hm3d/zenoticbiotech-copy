<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('order_index')->orderBy('name')->get();

        return response()->json(['categories' => CategoryResource::collection($categories)]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json(['category' => new CategoryResource($category)], 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->fill($request->validated())->save();

        return response()->json(['category' => new CategoryResource($category)]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $inUse = $category->products()->count();
        if ($inUse > 0) {
            return response()->json([
                'error' => "Cannot delete: {$inUse} product(s) still use this category",
            ], 409);
        }

        $id = (string) $category->id;
        $category->delete();

        return response()->json(['success' => true, 'id' => $id]);
    }
}
