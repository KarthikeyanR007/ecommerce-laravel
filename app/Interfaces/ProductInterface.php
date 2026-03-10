<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface ProductInterface
{
    public function toggleFavouriteForUser($productId, $userId, $isFavourite);
    public function getFavouritesForUser(int $userId);
    public function getSimilarProducts(int $productId);
    public function getSingleProduct(int $productId);
    public function getProductTitle(int $productId);
    public function getAllProducts();
    public function addProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_image);
    public function deleteProduct($product_id);
    public function getProduct($product_id);
    public function updateProduct($product_name,$product_description,$product_price,$discount_price,$category_id,$product_stock,$product_image,$product_id);
}
