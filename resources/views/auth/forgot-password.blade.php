@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-6 col-lg-5">

        {{-- Card --}}
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-5">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold text-dark">Forgot Password?</h1>
                    <p class="text-muted">
                        Enter your email address below, and we'll send you a verification code.
                    </p>
                </div>

                {{-- Success message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('resetpassword.sendotp') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email address</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-semibold">
                        Send OTP
                    </button>
                </form>

                {{-- Back to login --}}
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none text-warning">
                        &larr; Back to Login
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
