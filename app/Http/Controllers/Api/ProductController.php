<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }
}
