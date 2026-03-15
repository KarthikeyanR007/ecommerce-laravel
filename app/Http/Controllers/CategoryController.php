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

    public function addCategory(Request $req)
    {
        $category_name = $req->input('category_name');
        $category_description = $req->input('category_description');
        $category_image = $req->file('category_image');
        Log::info('test');
        $this->categoryService->addCategories($category_name, $category_description, $category_image);

        return response()->json([
          'message' => 'Category created successfully'
        ]);
    }

    public function getAllCategory()
    {
        $all_categories = $this->categoryService->getAllCategories();
        return response()->json([
            'data' => $all_categories,
            'message' => 'Get All Category successfully'
        ],200);
    }

    public function deleteCategory($category_id)
    {
        $category = $this->categoryService->deleteCategory($category_id);
        return response()->json([
            'data' => $category,
            'message' => 'Category Deleted successfully'
        ],200);
    }

    public function getCategory($category_id)
    {
        $categories = $this->categoryService->getCategory($category_id);
        
        return response()->json([
            'data' => $categories ,
            'message' => 'Get All Category successfully',
        ], 200);
    }

    public function updateCategory(Request $req, $category_id)
    {
        $category_id   = $category_id;
        $category_name = $req->input('category_name');
        $category_description = $req->input('category_description');
        $category_image = $req->file('category_image');
        $category = $this->categoryService->updateCategory($category_id, $category_name, $category_description, $category_image);
        return response()->json([
            'data' => $category,
            'message' =>'Category Deleted successfully'
        ],200);
    }
}
