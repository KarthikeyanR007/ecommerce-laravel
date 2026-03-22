<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Interfaces\OrderInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderRepository implements OrderInterface
{
    public function storeOrders($products, $address_id, $payment_method, $user_id, $orderImg)
    {
        $address_id = User::where('id', $user_id)->value('active_address');
        $column = match ($address_id) {
                        '1' => 'home_address',
                        '2' => 'office_address',
                        default => null,
                    };
        Log::info(['$orderImg '=>$orderImg]);
        $address = $column  ? User::where('id', $user_id)->value($column) : null;
        $orderImg = $orderImg;
        $order = Order::create([
                    'user_id' => $user_id,
                    'status'  => 'pending',
                    'payment_status' => 'unpaid',
                    'total_amount'   => 0,
                    'delivery_date' => now()->addDays(5),
                    'order_delivery_address' => $address,
                    'image_path' => $orderImg
                ]);

        $total = 0;
        foreach($products as $item){
            $product  = Product::find($item['id']);
            $subtotal = $product->product_price * $item['quantity'];
            OrderItem::create([
                'order_id'      => $order->order_id,
                'product_id'    => $item['id'],
                'quantity'      => $item['quantity'],
                'price'         => $product->product_price,
                'subtotal'      => $subtotal
            ]);

            $total += $subtotal;
        }

        $order->update(['total_amount' => $total]);

        return [
            'user_order_id' => $order->user_order_id,
            'delivery_date' => \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d'),
            'total_amount'  => $order->total_amount,
            'status'        => $order->status,
        ];

    }

    public function getOrders($user_id)
    {
         $orders = Order::where('orders.user_id', $user_id)
                    ->leftJoin('order_items', 'orders.order_id', '=', 'order_items.order_id')
                    ->leftJoin('delivery_boys', 'orders.delivery_boy_id', '=', 'delivery_boys.id')
                    ->select(
                        'orders.order_id',
                        'orders.user_order_id',
                        'orders.order_delivery_address',
                        'orders.status',
                        'orders.total_amount',
                        'orders.delivery_date',
                        'orders.delivery_boy_id',
                        'orders.image_path',
                        'delivery_boys.phone as delivery_boy_phone',
                        DB::raw('COUNT(order_items.order_id) as items_count')
                    )
                    ->groupBy(
                        'orders.order_id',
                        'orders.user_order_id',
                        'orders.order_delivery_address',
                        'orders.status',
                        'orders.total_amount',
                        'orders.delivery_date',
                        'orders.image_path',
                        'orders.delivery_boy_id',
                        'delivery_boys.phone'
                    )
                    ->orderBy('orders.created_at', 'desc')
                    ->get();

        return $orders->map(function ($order) {
            return [
                'id'       => $order->user_order_id,
                'address'  => $order->order_delivery_address,
                'items'    => $order->items_count,
                'status'   => $order->status,
                'price'    => (float) $order->total_amount,
                'dateTime' => Carbon::parse($order->delivery_date)
                                ->format('d M Y \a\t h:i A'),
                'tab'      => $order->status === 'delivered' ? 'past' : 'upcoming',
                'image'    => $order->image_path,
                'deliveryBoyId' => $order->delivery_boy_id,
                'deliveryBoyNumber' => $order->delivery_boy_phone,

            ];
        });
    }

    public function placeReorder($orderId)
    {
        Order::where('user_order_id', $orderId);
    }

    public function getAllOrder(int $perPage = 10)
    {
        return Order::with('items.product', 'user')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function assignDeliveryBoy($deliveryBoyId, $orderId)
    {
        Log::info(['deliveryBoyId' => $deliveryBoyId, 'orderId' => $orderId]);
        $order = Order::where('user_order_id', $orderId)->firstOrFail();
        $order->update([
            'delivery_boy_id' => $deliveryBoyId
        ]);
        return $order->fresh();
    }
}