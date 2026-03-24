<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth('api')->user();
        if ($user->role != 1) {
            auth('api')->logout();
            return response()->json(['error' => 'Access Denied. Admins only.'], 403);
        }
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'user' => auth('api')->user()
        ]);
    }

    public function register(Request $request)
    {
        try {
            Log::info(['request' => $request->all()]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = auth('api')->login($user);

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    
    public function logout()
    {
        auth('api')->logout();
        Log::info('User logged out');
        return response()->json(['message' => 'Logged out']);
    }
}
