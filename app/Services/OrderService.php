<?php

namespace App\Services;

use App\Interfaces\OrderInterface;
use Illuminate\Support\Collection;

class OrderService
{
    public function __construct(private OrderInterface $orders)
    {
    }

    public function storeOrders($products, $address_id, $payment_method, $user_id, $orderImg)
    {
        return $this->orders->storeOrders($products, $address_id, $payment_method, $user_id, $orderImg);
    }

    public function getOrders($user_id)
    {
        return $this->orders->getOrders($user_id);
    }

    public function placeReorder($orderId)
    {
        $orderId = ltrim($orderId, '#');
        return $this->orders->placeReorder($orderId);
    }

    public function getAllOrder()
    {
        return $this->orders->getAllOrder();
    }

    public function assignDeliveryBoy($deliveryBoyId, $orderId)
    {
        return $this->orders->assignDeliveryBoy($deliveryBoyId, $orderId);
    }
}