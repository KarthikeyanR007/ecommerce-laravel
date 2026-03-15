<?php

namespace App\Repositories;

use App\Interfaces\ProfileInterface;
use App\Models\User;

class ProfileRepository implements ProfileInterface
{
    public function getAllUsers($perPage = 10)
    {
       $users = User::where('status','1')->orderBy('created_at', 'desc')->paginate($perPage);
       return $users;
    }
}