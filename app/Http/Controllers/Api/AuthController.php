<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register
     */
    public function register(Request $request)
    {
        $request->validate([
            'username'      => ['required', 'string', 'unique:users,username', 'max:255'],
            'email'         => ['nullable', 'email', 'unique:users,email', 'max:255'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number'  => ['nullable', 'string', 'max:20'],
            'nida_id'       => ['nullable', 'string', 'size:20'],
        ]);

        $user = User::create([
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'nida_id'      => $request->nida_id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Registration successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('username', 'password'))) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Send OTP via Email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Rate limit: 1 OTP per minute
        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before requesting another OTP.',
            ], 429);
        }

        $otp = random_int(100000, 999999);

        $user->update([
            'otp_code'         => $otp,
            'otp_expires_at'   => Carbon::now()->addMinutes(5),
            'otp_attempts'     => 0,
            'otp_last_sent_at' => now(),
        ]);

        Mail::to($user->email)->queue(new OtpMail((string) $otp));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email',
        ]);
    }

    /**
     * Reset Password using OTP
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'otp'      => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp_attempts >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Too many invalid attempts. Request a new OTP.',
            ], 403);
        }

        if (
            $user->otp_code !== $request->otp ||
            now()->greaterThan($user->otp_expires_at)
        ) {
            $user->increment('otp_attempts');

            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        $user->update([
            'password'        => Hash::make($request->password),
            'otp_code'        => null,
            'otp_expires_at'  => null,
            'otp_attempts'    => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful.',
        ]);
    }
}
