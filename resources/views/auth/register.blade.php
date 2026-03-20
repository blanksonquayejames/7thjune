@extends('layouts.store')
@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="7th June Logo" height="60" class="mb-2" style="object-fit: contain;">
                        <h4 class="fw-bold mt-2">Create Account</h4>
                        <p class="text-muted small">Join 7TH JUNE and start shopping</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus placeholder="John Doe">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required placeholder="you@example.com">
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

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-custom"
                                   required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-3">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted mb-0">Already have an account?
                            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Log In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
