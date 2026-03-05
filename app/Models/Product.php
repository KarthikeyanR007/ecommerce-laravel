<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_discount',
        'product_stock',
        'product_image',
        'product_status',
        'category_id',
    ];

    public function favouritedBy()
    {
            return $this->belongsToMany(
                User::class,
                'favourites',
                'product_id',     // foreign key on favourites table
                'user_id',        // foreign key on favourites table
                'product_id',     // local key on products table
                'id'              // local key on users table
            );
    }
}
