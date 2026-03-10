<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeliveryBoyService;

class DeliveryBoyController extends Controller
{
    public function __construct(private DeliveryBoyService $deliveryboyService)
    {
    }

    public function getAllDeliveryBoys()
    {
        return $this->deliveryboyService->getAllDeliveryBoys();
    }
}