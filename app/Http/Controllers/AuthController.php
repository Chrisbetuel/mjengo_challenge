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

    // ✅ Store email for OTP verify page
    session([
        'otp_email' => $user->email,
    ]);

    // ✅ Send OTP (sync for now)
    Mail::to($user->email)->send(new OtpMail((string) $otp));

    // ✅ Redirect to OTP verify page
    return redirect('/otp-verify')->with('success', 'OTP sent to your email.');
}

    public function showResetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp_attempts >= 5) {
            return back()->withErrors([
                'otp' => 'Too many invalid attempts. Please request a new OTP.',
            ])->withInput();
        }

        if ($user->otp_code !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            $user->increment('otp_attempts');
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.',
            ])->withInput();
        }

        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
            'otp_attempts'   => 0,
        ]);

        return redirect('/login')->with('success', 'Password reset successful. You can now log in.');
    }

    // ---------------------
// OTP VERIFICATION
// ---------------------

/**
 * Show the OTP verification page
 */
public function showOtpVerifyForm()
{
    if (!session('otp_email')) {
        return redirect()->route('resetpassword.request')
            ->with('error', 'Please enter your email first.');
    }

    return view('auth.otp');
}

/**
 * Verify the submitted OTP
 */
public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $email = session('otp_email');
    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect()->route('resetpassword.email')
            ->with('error', 'Invalid session. Please enter your email again.');
    }

    // Check OTP attempts
    if ($user->otp_attempts >= 5) {
        return redirect()->route('resetpassword.email')
            ->with('error', 'Too many invalid attempts. Please request a new OTP.');
    }

    // Check OTP and expiration
    if ($user->otp_code != $request->otp || now()->greaterThan($user->otp_expires_at)) {
        $user->increment('otp_attempts');
        return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput();
    }

    // OTP is valid
    session(['otp_verified_email' => $user->email]); // store for reset step
    return redirect()->route('resetpassword')->with('success', 'OTP verified. You can now reset your password.');
}

}
