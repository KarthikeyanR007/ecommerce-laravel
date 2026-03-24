<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function addFavourite(Request $req)
    {
        Log::info($req->all());
        $productId = (int) $req->input('product_id');
        $isFavourite = $req->input('is_favourite');
        Log::info('is called');
        $this->productService->toggleFavourite($productId,$isFavourite);

        return response()->json([
            'message' => 'favourite added successfully',
        ]);
    }

    public function getFavourite($user_id)
    {
        $favourites = $this->productService->getFavourites((int) $user_id);

        return response()->json($favourites);
    }

    public function getSimilarProduct($product_id)
    {
        $similar = $this->productService->getSimilarProducts((int) $product_id);

        return response()->json($similar);
    }

    public function getSingleProduct($product_id)
    {
        $productDetails = $this->productService->getSingleProduct((int) $product_id);
        return $productDetails;
    }

    public function getProductTitle($product_id)
    {
        $productTitle = $this->productService->getProductTitle((int) $product_id);
        return $productTitle;
    }

    public function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
        
        return response()->json([
            'data'         => $products->items(),
            'total'        => $products->total(),
            'per_page'     => $products->perPage(),
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'message'      => 'Products fetched successfully',
        ]);
    }

    public function addProduct(Request $req)
    {
        $product_name = $req->input('product_name');
        $product_description = $req->input('product_description');
        $product_price = $req->input('product_price');
        $discount_price = $req->input('discount_price');
        $category_id = $req->input('category_id');
        $product_image = $req->file('product_image');
        $product = $this->productService->addProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_image);
        
        return response()->json([
            'data' => $product,
            'message' =>'Get All Products successfully'
        ],200);
    }

    public function deleteProduct($product_id)
    {
        $product = $this->productService->deleteProduct($product_id);

        return response()->json([
         'data' => $product,
         'message' =>'Get All Products successfully'
        ],200);
    }

    public function getProduct($product_id)
    {
        $product = $this->productService->getProduct($product_id);
        return  response()->json([
         'data' => $product,
         'message' =>'Get Products successfully'
        ],200);
    }

    public function updateProduct(Request $req,$product_id)
    {
        $product_id          = $product_id;
        $product_name        = $req->input('product_name');
        $product_description = $req->input('product_description');
        $product_price       = $req->input('product_price');
        $discount_price      = $req->input('product_discount');
        $category_id         = $req->input('category_id');
        $product_stock       = $req->input('product_stock');
        $product_image       = $req->file('product_image') ?? $req->input('product_image');
        $product = $this->productService->updateProduct($product_name,$product_description,$product_price,$discount_price,$category_id,$product_stock,$product_image,$product_id);
        
        return response()->json([
         'data' => $product,
         'message' =>'Products Updated successfully'
        ],200);
    }
}