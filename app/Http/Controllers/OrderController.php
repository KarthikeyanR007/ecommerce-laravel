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

        $orderData      = $this->orderService->storeOrders($products, $address_id, $payment_method, $user_id);
        return $orderData;
    }

    public function getOrder()
    {
        $user_id = auth()->id();
        return $this->orderService->getOrders($user_id);
    }

    public function reOrder(Request $req)
    {
        $orderId = $req->input('order_id');
        return $this->orderService->placeReorder($orderId);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
