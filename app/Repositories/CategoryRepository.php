<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
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
}