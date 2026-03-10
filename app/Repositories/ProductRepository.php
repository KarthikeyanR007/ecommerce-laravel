<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Models\Favourite;
use App\Interfaces\ProductInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductRepository implements ProductInterface
{
    public function toggleFavouriteForUser($productId, $userId,$isFavourite)
    {
        // $user = User::findOrFail($userId);
        $isFav = Favourite::where('user_id', $userId)->where('product_id', $productId)->exists();
        Log::info(['isFav '=>$isFav,'$isFavourite '=>$isFavourite ]);
        if ($isFav || ($isFavourite != true)) {
            Favourite::where('user_id', $userId)->where('product_id', $productId)->delete();
        } else if($isFavourite) {
            Favourite::create(['user_id' => $userId,'product_id' => $productId]);
        }
    }

    public function getFavouritesForUser(int $userId): Collection
    {
        return Product::whereHas('favouritedBy', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('product_status', 1)
            ->get();
    }

    public function getSimilarProducts(int $productId): Collection
    {
        $product = Product::where('product_id', $productId)->first();

        if (!$product) {
            return collect();
        }

        return Product::where('category_id', $product->category_id)
            ->where('product_status', 1)
            ->where('product_id', '!=', $productId)
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
    }

    public function getSingleProduct($productId)
    {
        $product = Product::select([
                        'product_name as name',
                        'product_description as description',
                        'product_price as price',
                        'product_image as image',
                        'product_isFavourite as favourite',
                        'product_discount as discount', 
                        'category_id'
                    ])
                    ->where('product_status', 1)
                    ->find($productId);

        return $product;
    }

    public function getProductTitle($productId)
    {
        $productTitle = Product::where('product_status', 1)->where('product_id',$productId)->value('product_name');
        return $productTitle;
    }

    public function getAllProducts(int $perPage = 10)
    {
        $products = Product::where('product_status',1)->orderBy('created_at', 'desc')->paginate($perPage);
        return $products;
    }

    public function addProduct($product_name, $product_description, $product_price, $discount_price,$category_id, $product_image)
    {
       $product = Product::create([
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'product_discount' => $discount_price,
            'category_id' => $category_id,
        ]);

        return $product;
    }
    
    public function deleteProduct($product_id)
    {
        $product = Product::where('product_id', $product_id)->update(['product_status' => 0]);
        return $product;
    }

    public function getProduct($product_id)
    {
        return Product::where('product_id', $product_id)->where('product_status',1)->first();
    }

    public function updateProduct($product_name, $product_description, $product_price, $discount_price, $category_id, $product_stock, $product_image, $product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return null;
        }

        $product->update([
            'product_name'        => $product_name,
            'product_description' => $product_description,
            'product_price'       => $product_price,
            'product_discount'    => $discount_price,
            'product_stock'       => $product_stock,
            'product_image'       => $product_image,
            'category_id'         => $category_id,
        ]);

        return $product;
    }
}
