<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function storeOrder(Request $req)
    {
        $products       = $req->input('products');
        $address_id     = $req->input('address_id');
        $payment_method = $req->input('payment_method');
        $user_id        = auth()->id();
        $orderImg       = $req->input('orderImage');

        $orderData      = $this->orderService->storeOrders($products, $address_id, $payment_method, $user_id,$orderImg);
        return $orderData;
    }

    public function getOrder()
    {
        $user_id = auth()->id();
        return $this->orderService->getOrders($user_id);
    }

    public function placeReorder($orderId)
    {
        return $this->orderService->placeReorder($orderId);
    }

    public function getAllOrder()
    {
        $orders = $this->orderService->getAllOrder();

        $data = collect($orders->items())->map(function ($order) {
            return [
                'order_id'               => $order->order_id,
                'user_order_id'          => $order->user_order_id,
                'user_id'                => $order->user_id,
                'user_name'              => $order->user?->name,
                'user_email'             => $order->user?->email,
                'total_amount'           => $order->total_amount,
                'delivery_date'          => $order->delivery_date,
                'order_delivery_address' => $order->order_delivery_address,
                'delivery_boy_id'        => $order->delivery_boy_id,
                'payment_status'         => $order->payment_status,
                'order_status'           => $order->status_label,
                'created_at'             => $order->created_at,
                'updated_at'             => $order->updated_at,
                'items'                  => $order->items->map(fn($item) => [
                            'order_item_id' => $item->order_item_id,
                            'order_id'      => $item->order_id,
                            'product_id'    => $item->product_id,
                            'product_name'  => $item->product?->product_name,
                            'quantity'      => $item->quantity,
                            'price'         => $item->price,
                            'subtotal'      => $item->subtotal,
                ]),
            ];
        });

        return response()->json([
            'data'         => $data,
            'total'        => $orders->total(),
            'per_page'     => $orders->perPage(),
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
            'message'      => 'Orders fetched successfully',
        ]);
    }

    public function assignDeliveryBoy(Request $req, $orderId)
    {
        $deliveryBoyId = $req->input('delivery_boy_id');
        $order = $this->orderService->assignDeliveryBoy($deliveryBoyId, $orderId);

        return response()->json([
            'data'    => $order,
            'message' => 'Delivery boy assigned successfully',
        ]);
    }

    public function orderCancel(Request $req,$orderId)
    {
        $ord_cancel_reason = $req->input('reason');
        $order = $this->orderService->orderCancel($orderId, $ord_cancel_reason);
        return response()->json([
            'data' => $order,
            'message' => 'Order Cancelled successfully'
        ]);
    }

    public function getReorderData($orderId)
    {
        $order = $this->orderService->getReorderData($orderId);
        return response()->json([
            'data' => $order,
            'message' => 'Reorder Data Get successfully'
        ], 200);
    }
}
