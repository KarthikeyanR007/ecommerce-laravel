<?php

namespace App\Services;

use App\Interfaces\ProfileInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    public function __construct(private ProfileInterface $profile)
    {
    }

    public function getAllUsers()
    {
       return $this->profile->getAllUsers();
    }
}