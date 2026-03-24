<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface OrderInterface
{
    public function storeOrders($products, $address_id, $payment_method, $user_id, $orderImg);
    public function getOrders($user_id);
    public function placeReorder($orderId);
    public function getAllOrder();
    public function assignDeliveryBoy($deliveryBoyId, $orderId);
    public function orderCancel($orderId, $ord_cancel_reason);
    public function getReorderData($orderId);
}