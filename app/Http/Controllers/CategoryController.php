<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getProductByCategory(Request $request)
    {
        $categoryId = $request->route('categoryId');
        $products = Product::where('category_id', $categoryId)->where('product_status', 1)->get();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'Products not found'], 404);
        }
        return response()->json($products, 200);
    }

    /**
     * Display the specified resource.
     */
    public function getCategoryForHome(Category $category)
    {
        $category = Category::where('category_status', true)->latest()->take(6)->get();
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
