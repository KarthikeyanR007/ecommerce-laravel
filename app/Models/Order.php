<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int'; 

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrder = Order::latest('order_id')->first();
            $nextId = $lastOrder ? $lastOrder->order_id + 1 : 1;
            $order->user_order_id = 'ORD-' . date('Y') . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    protected $fillable = [
      'user_id',
      'user_order_id',
      'total_amount',
      'delivery_date',
      'status',
      'payment_status',
      'order_delivery_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
