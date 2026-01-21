@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-6 col-lg-5">

        {{-- Card --}}
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-5">

                {{-- Header --}}
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold text-dark">Reset Password</h1>
                    <p class="text-muted">
                        Enter your new password below to reset your account.
                    </p>
                </div>

                {{-- Success / Error Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ url('/reset-password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">New Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Enter new password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input
                            type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            required
                        >
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        Reset Password
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="text-center mt-4 small">
                    <a href="{{ route('login') }}" class="text-success fw-medium">&larr; Back to Login</a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
