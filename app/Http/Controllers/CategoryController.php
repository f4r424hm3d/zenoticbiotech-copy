<?php

namespace App\Http\Controllers;

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
}
