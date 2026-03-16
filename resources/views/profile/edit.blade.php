@extends('layouts.store')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <div class="container">
        <h2><i class="bi bi-person-circle me-2"></i>My Profile</h2>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Profile Information -->
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1"><i class="bi bi-person me-2 text-primary"></i>Profile Information</h5>
                    <p class="text-muted small mb-4">Update your name and email address.</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-custom @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-custom @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>

                        @if(session('status') === 'profile-updated')
                            <span class="text-success ms-3 small fw-semibold">
                                <i class="bi bi-check-circle me-1"></i>Saved!
                            </span>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-lg-6">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2 text-primary"></i>Update Password</h5>
                    <p class="text-muted small mb-4">Use a strong, random password to stay secure.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Current Password</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control form-control-custom @error('current_password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">New Password</label>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-custom @error('password', 'updatePassword') is-invalid @enderror"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-custom"
                                   autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-lg me-2"></i>Update Password
                        </button>

                        @if(session('status') === 'password-updated')
                            <span class="text-success ms-3 small fw-semibold">
                                <i class="bi bi-check-circle me-1"></i>Updated!
                            </span>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="card card-custom border border-danger border-opacity-25">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Delete Account</h5>
                            <p class="text-muted small mb-0">Once deleted, all your data will be permanently removed.</p>
                        </div>
                        <button class="btn btn-outline-danger" style="border-radius:10px" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash3 me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px; border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-muted">This action cannot be undone. Please enter your password to confirm.</p>
                    <input type="password" name="password" class="form-control form-control-custom"
                           placeholder="Enter your password" required>
                    @error('password', 'userDeletion')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" style="border-radius:10px" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="border-radius:10px">
                        <i class="bi bi-trash3 me-2"></i>Delete My Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
