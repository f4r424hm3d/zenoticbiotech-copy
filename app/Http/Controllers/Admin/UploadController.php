<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * Accept an image file from the admin product form, store it under
     * public/uploads/products, and return its absolute URL so it can be
     * saved as the product's image_url (same field the URL input uses).
     */
    public function image(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'], // 5 MB
        ]);

        $file = $request->file('image');
        $name = uniqid('prod_', true).'.'.strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move(public_path('uploads/products'), $name);

        return response()->json([
            'url' => asset('uploads/products/'.$name),
        ], 201);
    }
}
