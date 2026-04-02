@extends('layouts.store')
@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="7th June Computers Logo" height="60" class="mb-2" style="object-fit: contain;">
                        <h4 class="fw-bold mt-2">Welcome Back</h4>
                        <p class="text-muted small">Login to your 7th June Computers account</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success alert-custom small">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-custom @error('password') is-invalid @enderror"
                                   required placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Remember me</label>
                            </div>
                            @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-semibold">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted mb-2">Don't have an account?</p>
                        <a href="{{ route('register') }}" class="btn btn-secondary-custom w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Create an Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
