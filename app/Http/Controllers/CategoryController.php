<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {

    }

    public function getProductByCategory(Request $request)
    {
        $categoryId = $request->route('categoryId');
        $products = Product::where('category_id', $categoryId)->where('product_status', 1)->get();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'Products not found'], 404);
        }
        return response()->json($products, 200);
    }

    public function getCategoryForHome()
    {
        $home_categories = $this->categoryService->getCategoriesForHome();
        return response()->json($home_categories);
    }

    public function getCategoryForAllItem()
    {
        $allItem_categories = $this->categoryService->getCategoriesForAllItem();
        return response()->json($allItem_categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
