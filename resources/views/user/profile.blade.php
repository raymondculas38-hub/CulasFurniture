@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container py-4 my-2">
    <div class="mb-4 pb-2 border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active">Account Settings</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0 font-serif">Customer Profile</h2>
    </div>

    <div class="row g-4">
        <!-- Profile Info Form -->
        <div class="col-lg-6">
            <div class="luxury-card p-4 h-100">
                <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-person me-2 text-warning"></i> Personal Details</h5>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control bg-light" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address (Read-only)</label>
                        <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control bg-light" value="{{ old('phone', $user->phone) }}" placeholder="+1 (555) 000-0000">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Default Delivery Address</label>
                        <textarea name="address" rows="3" class="form-control bg-light" placeholder="Street, building, apartment, city...">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-luxury btn-sm px-4">
                        Save Profile Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Password Change Form -->
        <div class="col-lg-6">
            <div class="luxury-card p-4 h-100">
                <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-lock me-2 text-warning"></i> Change Password</h5>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control bg-light" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control bg-light" required minlength="8">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-light" required minlength="8">
                    </div>

                    <button type="submit" class="btn btn-luxury btn-sm px-4">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
