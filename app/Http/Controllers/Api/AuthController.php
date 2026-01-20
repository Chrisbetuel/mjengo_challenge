<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'unique:users,username', 'max:255'],
            'email'    => ['nullable', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'nida_id'      => ['nullable', 'string', 'size:20'],
        ]);

        $user = User::create([
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone_number'  => $request->phone_number,
            'nida_id'       => $request->nida_id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success'       => true,
            'message'       => 'Registration successful',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => [
                'id'        => $user->id,
                'username'  => $user->username,
                'email'     => $user->email,
                'phone_number' => $user->phone_number,
                'nida_id'      => $user->nida_id,
            ],
        ], 201);
    }

    /**
     * Handle login request
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
            'success'       => true,
            'message'       => 'Login successful',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => [
                'id'        => $user->id,
                'username'  => $user->username,
                'email'     => $user->email,
            ],
        ]);
    }

    /**
     * Handle logout request (revoke current token)
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
 * Send password reset link
 */
public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => ['required', 'email', 'exists:users,email'],
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email.',
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Unable to send reset link.',
    ], 500);
}
           /**
 * Reset user password
 */
public function resetPassword(Request $request)
{
    $request->validate([
        'token'    => ['required'],
        'email'    => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid or expired reset token.',
    ], 400);
}


}