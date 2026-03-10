<?php

namespace App\Services;

use App\Interfaces\DeliveryBoyInterface;
use Illuminate\Support\Collection;

class DeliveryBoyService
{
    public function __construct(private DeliveryBoyInterface $deliveryboys)
    {
    }

    public function getAllDeliveryBoys()
    {
        return $this->deliveryboys->getAllDeliveryBoys();
    }
}