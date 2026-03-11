<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface OrderInterface
{
    public function storeOrders($products, $address_id, $payment_method, $user_id);
    public function getOrders($user_id);
    public function placeReorder($orderId);
    public function getAllOrder();
    public function assignDeliveryBoy($deliveryBoyId, $orderId);
}