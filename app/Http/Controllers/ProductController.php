<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Public listing — published products only, optionally filtered by
     * segment (human|animal) and/or category (id or name).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with('category')->published();

        if ($request->filled('segment')) {
            $segment = strtolower((string) $request->query('segment'));
            if (! in_array($segment, ['human', 'animal'], true)) {
                return response()->json(['products' => []]);
            }
            $query->where('segment', $segment);
        }

        if ($request->filled('category')) {
            $categoryId = Category::resolveId($request->query('category'));
            if (! $categoryId) {
                return response()->json(['products' => []]);
            }
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('order_index')->orderByDesc('created_at')->get();

        return response()->json(['products' => ProductResource::collection($products)]);
    }
}
