@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-6 col-lg-5">

        {{-- Card --}}
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-5">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold text-dark">Enter Verification Code</h1>
                    <p class="text-muted">
                        We sent a 6-digit code to <strong>{{ session('otp_email') }}</strong>.<br>
                        Enter it below to continue.
                    </p>
                </div>

                {{-- Success / Error messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('password.otp.verify') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="otp" class="form-label fw-semibold">Verification Code</label>
                        <input
                            type="text"
                            class="form-control form-control-lg text-center fw-bold @error('otp') is-invalid @enderror"
                            id="otp"
                            name="otp"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            placeholder="------"
                            required
                        >
                        @error('otp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-semibold">
                        Verify Code
                    </button>
                </form>

                {{-- Resend & Back --}}
                <div class="text-center mt-4 small">
                    <p class="mb-1">
                        Didn't receive the code?
                        <a href="{{ route('password.otp.request') }}" class="text-warning fw-medium">Resend</a>
                    </p>
                    <p>
                        <a href="{{ route('login') }}" class="text-decoration-none text-warning">&larr; Back to Login</a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
