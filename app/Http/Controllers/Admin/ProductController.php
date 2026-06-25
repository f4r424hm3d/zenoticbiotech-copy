<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UpdateProductStatusRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('segment')) {
            $segment = strtolower((string) $request->query('segment'));
            if (in_array($segment, ['human', 'animal'], true)) {
                $query->where('segment', $segment);
            }
        }

        if ($request->filled('search')) {
            $search = (string) $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('order_index')->orderByDesc('created_at')->get();

        return response()->json(['products' => ProductResource::collection($products)]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['segment'] = $data['segment'] ?? 'human';
        $data['status'] = ($data['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $data['order_index'] = $data['order_index'] ?? 0;
        $data['features'] = $data['features'] ?? [];

        $product = Product::create($data);
        $product->load('category');

        return response()->json(['product' => new ProductResource($product)], 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json(['product' => new ProductResource($product)]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        if (array_key_exists('status', $data)) {
            $data['status'] = $data['status'] === 'published' ? 'published' : 'draft';
        }

        $product->fill($data)->save();
        $product->load('category');

        return response()->json(['product' => new ProductResource($product)]);
    }

    public function updateStatus(UpdateProductStatusRequest $request, Product $product): JsonResponse
    {
        $product->status = $request->validated()['status'] === 'published' ? 'published' : 'draft';
        $product->save();
        $product->load('category');

        return response()->json(['product' => new ProductResource($product)]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $id = (string) $product->id;
        $product->delete();

        return response()->json(['success' => true, 'id' => $id]);
    }
}
