@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-center">Reset Password</h1>

<form action="{{ url('/reset-password') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block mb-1">OTP</label>
        <input type="text" name="otp" value="{{ old('otp') }}" class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block mb-1">New Password</label>
        <input type="password" name="password" class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
    </div>
    <button type="submit" class="w-full bg-green-600 text-white p-2 rounded">Reset Password</button>
</form>

<p class="mt-4 text-center"><a href="{{ url('/login') }}" class="text-blue-600">Back to Login</a></p>
@endsection
