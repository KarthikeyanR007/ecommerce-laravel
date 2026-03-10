<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Interfaces\CategoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryInterface
{
    public function getCategoriesForAllItem(): Collection
    {
        return DB::table('categories as c')
            ->join('products as p', function ($join) {
                $join->on('p.category_id', '=', 'c.category_id')
                    ->where('p.product_status', 1);
            })
            ->select('c.*')
            ->distinct()
            ->get();
    }

    public function getCategoriesForHome(): Collection
    {
        return DB::table('categories as c')
            ->join('products as p', function ($join) {
                $join->on('p.category_id', '=', 'c.category_id')
                    ->where('p.product_status', 1);
            })
            ->select('c.*')
            ->distinct()
            ->limit(6)
            ->get(); 
    }

    public function addCategories($category_name, $category_description, $imagePath)
    {
        $category = Category::create([
            'category_name' => $category_name,
            'category_description' => $category_description,
            'category_image' => $imagePath
        ]);

        return $category;
    }

    public function getAllCategories(int $perPage = 10)
    {
        return Category::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function deleteCategory($category_id)
    {
        return Category::where('category_id', $category_id)->update(['category_status' => 0]);
    }

    public function getCategory($category_id)
    {
        return Category::where('category_id', $category_id)->first();
    }

    public function updateCategory($category_id, $category_name, $category_description, $imagePath)
    {
            $category = Category::where('category_id', $category_id)->update([
                            'category_name' => $category_name,
                            'category_description' => $category_description,
                            'category_image' => $imagePath
                        ]);

            return $category;
    }
}