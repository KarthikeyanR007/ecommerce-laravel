<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService)
    {
    }

    public function addUserDetails(Request $request, $userId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'homeAddress' => 'nullable|string',
            'officeAddress' => 'nullable|string',
        ]);

        $user = User::where('status', '1')->findOrFail($userId);
        Log::info([
            'phone ' => $validated['phone'],
            'home_address' => $validated['homeAddress'],
            'office_address' => $validated['officeAddress'],
        ]);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'home_address' => $validated['homeAddress'],
            'office_address' => $validated['officeAddress'],
        ]);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function getUserDetails($userId){
        $userData = User::where('status','1')->findOrFail($userId);
        return response()->json([
            'message' => 'User data get successfully',
            'data' => $userData
        ]);
    }

    public function getActiveAddress($userId){
        $activeAddress = User::where('status','1')->select(
                                                   'home_address',
                                                   'office_address',
                                                   'active_address'
                                                   )->findOrFail($userId);
        return response()->json([
            'message' => 'User data get successfully',
            'data' => $activeAddress
        ]);
    }

    public function changeActiveAddress(Request $request,$userId){
        $current_address = $request->activeAddress;
        $user = User::where('status','1')->findOrFail($userId);
        Log::info(['current_address' => $current_address]);
        $user->update(['active_address' => $current_address]);
        return response()->json([
            'message' => 'User data get successfully',
            'data' => $user->fresh()
        ]);
    }

    public function getAllUsers()
    {
        $users = $this->profileService->getAllUsers();
        return response()->json([
            'data' => [
                'data'         => $users->items(),
                'total'        => $users->total(),
                'per_page'     => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
            ],
            'message' => 'Get All Users successfully',
        ]);
    }

}
