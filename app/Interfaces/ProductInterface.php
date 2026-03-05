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
}
