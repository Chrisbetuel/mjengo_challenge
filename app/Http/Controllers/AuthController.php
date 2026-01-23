<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ---------------------
    // LOGIN / LOGOUT
    // ---------------------

    public function showLoginForm()
    {
        return view('auth.login');
    }

    private function generateUserId(): string
{
    do {
        $userId = 'MJE' . random_int(10000, 99999);
    } while (User::where('user_id', $userId)->exists());

    return $userId;
}


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Access denied. Admin privileges required.',
                ]);
            }
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ---------------------
    // REGISTRATION
    // ---------------------

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string',
            'nida_id' => 'nullable|string|unique:users',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'user_id' => $this->generateUserId(),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'nida_id' => $request->nida_id,
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Registration successful!');
    }

    // ---------------------
    // OTP PASSWORD RESET
    // ---------------------

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Rate limit: 1 OTP per minute
        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < 60) {
            return back()->withErrors([
                'email' => 'Please wait a minute before requesting another OTP.',
            ])->withInput();
        }

        $otp = random_int(100000, 999999);

        $user->update([
            'otp_code'         => $otp,
            'otp_expires_at'   => now()->addMinutes(5),
            'otp_attempts'     => 0,
            'otp_last_sent_at' => now(),
        ]);

        // Store email in session for OTP verification
        session(['otp_email' => $user->email]);

        // Send OTP via email
        Mail::to($user->email)->send(new OtpMail((string) $otp));

        return redirect()->route('email.otp')
            ->with('success', 'OTP sent to your email.');
    }

    /**
     * Show OTP verification form
     */
   public function showOtpVerifyForm()
{
    if (!session()->has('otp_email')) {
        return redirect('/forgot-password')
            ->with('error', 'Please enter your email first.');
    }

    return view('auth.otp');
}

/**
 * Verify submitted OTP
 */
public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $email = session('otp_email');
    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect('/forgot-password')
            ->with('error', 'Invalid session. Please request a new OTP.');
    }

    // Too many attempts
    if ($user->otp_attempts >= 5) {
        return redirect('/forgot-password')
            ->with('error', 'Too many invalid attempts. Please request a new OTP.');
    }

    // OTP invalid or expired
    if (
        $user->otp_code != $request->otp ||
        now()->greaterThan($user->otp_expires_at)
    ) {
        $user->increment('otp_attempts');

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP.'
        ]);
    }

    // ✅ OTP valid → clear OTP + allow password reset
    $user->update([
        'otp_code' => null,
        'otp_expires_at' => null,
        'otp_attempts' => 0,
    ]);

    session([
        'otp_verified_email' => $user->email
    ]);

    return redirect('/reset-password')
        ->with('success', 'OTP verified. You can now reset your password.');
}


    /**
     * Show reset password form after OTP verification
     */
    public function showResetPasswordForm()
    {
        if (!session('otp_verified_email')) {
            return redirect()->route('resetpassword.request')
                ->with('error', 'Please verify OTP first.');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password with verified OTP
     */
    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('otp_verified_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('resetpassword.request')
                ->with('error', 'Invalid session. Please enter your email again.');
        }

        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
            'otp_attempts'   => 0,
        ]);

        // Clear OTP sessions
        session()->forget(['otp_email', 'otp_verified_email']);

        return redirect()->route('login')->with('success', 'Password reset successful. You can now log in.');
    }
}
