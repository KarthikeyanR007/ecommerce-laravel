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
}
