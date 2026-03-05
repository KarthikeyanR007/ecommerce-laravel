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
}
