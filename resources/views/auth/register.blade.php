@extends('layouts.app')

@section('title', 'Create an Account')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="luxury-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="badge bg-warning bg-opacity-25 text-warning-emphasis px-3 py-1 rounded-pill small fw-bold mb-2">JOIN SANTI</span>
                    <h2 class="fw-bold mb-1">Create Customer Account</h2>
                    <p class="text-muted small">Experience seamless furniture ordering with Cash on Delivery</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label small fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input id="name" type="text" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="e.g. Alex Harrison">
                        </div>
                        @error('name')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input id="email" type="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                        </div>
                        @error('email')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label small fw-semibold">Contact Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input id="phone" type="text" class="form-control bg-light border-start-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                            </div>
                            @error('phone')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="address" class="form-label small fw-semibold">Delivery Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                <input id="address" type="text" class="form-control bg-light border-start-0 @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="Street, City, Zip">
                            </div>
                            @error('address')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label small fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input id="password" type="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password-confirm" class="form-label small fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input id="password-confirm" type="password" class="form-control bg-light border-start-0" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-luxury w-100 py-2 mb-3">
                        Create Account & Start Shopping
                    </button>

                    <div class="text-center small text-muted">
                        Already have an account? <a href="{{ route('login') }}" class="text-warning-emphasis fw-bold text-decoration-none">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
