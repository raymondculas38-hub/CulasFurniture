@extends('layouts.admin')

@section('title', 'Admin Profile Settings')
@section('page-title', 'Administrator Account Settings')
@section('page-subtitle', 'Manage administrator credentials and contact details')

@section('content')
<div class="row g-4">
    <!-- Admin Info Form -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-3">Profile Information</h5>
            
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Full Name</label>
                    <input type="text" name="name" class="form-control bg-light" value="{{ old('name', $admin->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Admin Email</label>
                    <input type="email" name="email" class="form-control bg-light" value="{{ old('email', $admin->email) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase">Phone</label>
                    <input type="text" name="phone" class="form-control bg-light" value="{{ old('phone', $admin->phone) }}">
                </div>

                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Password Change Form -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-3">Security & Password</h5>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">Current Password</label>
                    <input type="password" name="current_password" class="form-control bg-light" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase">New Password</label>
                    <input type="password" name="password" class="form-control bg-light" required minlength="8">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control bg-light" required minlength="8">
                </div>

                <button type="submit" class="btn btn-dark px-4">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
