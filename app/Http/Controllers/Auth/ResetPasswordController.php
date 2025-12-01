<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);
        $user->update(['reset_token' => $token]);

        // Send email (simplified - in production, use a proper mail service)
        Mail::raw("Your password reset link: " . route('password.reset', $token), function ($message) use ($user) {
            $message->to($user->email)->subject('Password Reset');
        });

        return back()->with('status', 'Password reset link sent to your email.');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('reset_token', $request->token)->first();

        if (!$user) {
            return back()->withErrors(['token' => 'Invalid reset token.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
        ]);

        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}
