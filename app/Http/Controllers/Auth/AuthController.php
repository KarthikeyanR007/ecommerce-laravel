<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    private int $maxAttempts = 5;
    private int $otpTtl = 300;

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
        $username = $request->input('email');
        $email = $username . '@gmail.com';
        $password = $request->input('password');
        $credentials = compact('email', 'password');
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
            $name = $request->name;
            $email = $request->email;
            $pasword = $request->password;
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($pasword),
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

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid email address',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email = $request->input('email');
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'User with this email is already registered.',
            ], 409);
        }

        // Rate limit: max 3 OTP sends per email per 10 minutes
        $rateLimitKey = "otp_rate:{$email}";
        $sendCount    = Cache::get($rateLimitKey, 0);

        if ($sendCount >= 3) {
            return response()->json([
                'message' => 'Too many OTP requests. Please wait 10 minutes.',
            ], 429);
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache
        $cacheKey = "otp:{$email}";
        Cache::put($cacheKey, [
            'otp'      => $otp,
            'attempts' => 0,
            'verified' => false,
        ], $this->otpTtl);

        // Increment rate limit counter (expires in 10 min)
        Cache::put($rateLimitKey, $sendCount + 1, 600);

        // Send OTP email
        try {
            Log::info("Sending OTP to email: {$otp} for {$email}");
            Mail::to($email)->send(new OtpMail($otp, $this->otpTtl));
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to send OTP email. Please try again.',
            ], 500);
        }

        Log::info("OTP sent to email: {$email}");

        return response()->json([
            'message'    => 'OTP sent successfully',
            'expires_in' => $this->otpTtl,
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'otp'   => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid input',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email    = $request->input('email');
        $inputOtp = $request->input('otp');
        $cacheKey = "otp:{$email}";

        // Check if OTP exists (not expired)
        $data = Cache::get($cacheKey);

        if (!$data) {
            return response()->json([
                'message' => 'OTP expired or not requested. Please request a new OTP.',
            ], 400);
        }

        // Check max attempts (brute-force protection)
        if ($data['attempts'] >= $this->maxAttempts) {
            Cache::forget($cacheKey);
            return response()->json([
                'message' => 'Too many incorrect attempts. Please request a new OTP.',
            ], 429);
        }

        // Wrong OTP — increment attempts
        if ($data['otp'] !== $inputOtp) {
            $data['attempts']++;
            Cache::put($cacheKey, $data, $this->otpTtl);

            $remaining = $this->maxAttempts - $data['attempts'];
            return response()->json([
                'message'           => 'Invalid OTP.',
                'attempts_remaining' => $remaining,
            ], 400);
        }

        // ✅ OTP correct — mark verified, delete OTP from cache
        Cache::forget($cacheKey);

        // Store a short-lived "email verified" token so register API
        // can confirm this email was actually verified
        $verifiedKey = "email_verified:{$email}";
        Cache::put($verifiedKey, true, 600); // valid 10 min to complete registration

        Log::info("OTP verified successfully for email: {$email}");

        return response()->json([
            'message'  => 'OTP verified successfully',
            'verified' => true,
        ], 200);
    }

    public function checkUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid username',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $name = $request->input('name');
        $email = $name . '@gmail.com';

        $exists = User::where('email', $email)->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => !$exists ? 'Username is available' : 'Username is already taken',
        ], 200);
    }
}
