<?php

namespace App\Repositories;

use App\Interfaces\DeliveryBoyInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryBoy;

class DeliveryBoyRepository implements DeliveryBoyInterface
{
    public function getAllDeliveryBoys(int $perPage = 10)
    {
        return DeliveryBoy::orderBy('created_at', 'desc')->paginate($perPage);
    }
}