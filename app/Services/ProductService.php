<?php

namespace App\Services;

use App\Interfaces\ProductInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(private ProductInterface $products)
    {
    }

    public function toggleFavourite(int $productId,$isFavourite)
    {
        $user = auth()->user();
        // Log::info($user);
        if (!$user) {
            return;
        }

        $this->products->toggleFavouriteForUser($productId, $user->id,$isFavourite);
    }

    public function getFavourites(int $userId): Collection
    {
        return $this->products->getFavouritesForUser($userId);
    }

    public function getSimilarProducts(int $productId): Collection
    {
        return $this->products->getSimilarProducts($productId);
    }

    public function getSingleProduct(int $productId)
    {
        return $this->products->getSingleProduct($productId);
    }

    public function getProductTitle(int $productId)
    {
        return $this->products->getProductTitle($productId);
    }

    public function getAllProducts(int $perPage = 10)
    {
        return $this->products->getAllProducts($perPage);
    }

    public function addProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_image)
    {
        $imagePath = null;
        if ($product_image) {
            $user_id = auth()->id();
            $timestamp = time();
            $extension = $product_image->getClientOriginalExtension();
            $fileName = $user_id . '_' . $timestamp . '.' . $extension;
            $product_image->storeAs('products', $fileName, 'public');
            $imagePath = 'storage/products/' . $fileName;
        }
             $imagePath ;
        return $this->products->addProduct($product_name, $product_description, $product_price, $discount_price, $category_id,$imagePath);
    }

    public function deleteProduct($product_id)
    {
        return $this->products->deleteProduct($product_id);
    }

    public function getProduct($product_id)
    {
        return $this->products->getProduct($product_id);
    }

    public function updateProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_stock, $product_image, $product_id)
    {
        if ($product_image) {
            if ($product_image instanceof \Illuminate\Http\UploadedFile) {
                $user_id = auth()->id();
                $timestamp = time();
                $extension = $product_image->getClientOriginalExtension();
                $fileName = $user_id . '_' . $timestamp . '.' . $extension;
                $product_image->storeAs('products', $fileName, 'public');
                $product_image = 'storage/products/' . $fileName;
            }
        }

        return $this->products->updateProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_stock, $product_image, $product_id);
    }
}
