@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="luxury-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="badge bg-warning bg-opacity-25 text-warning-emphasis px-3 py-1 rounded-pill small fw-bold mb-2">WELCOME BACK</span>
                    <h2 class="fw-bold mb-1">Customer Sign In</h2>
                    <p class="text-muted small">Access your orders, track deliveries, and leave reviews</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input id="email" type="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email', 'santi@gmail.com') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                        </div>
                        @error('email')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input id="password" type="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" name="password" required value="password" placeholder="••••••••">
                        </div>
                        @error('password')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                            <label class="form-check-label small text-muted" for="remember">
                                Keep me signed in
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-luxury w-100 py-2 mb-3">
                        Sign In to Account
                    </button>

                    <div class="text-center small text-muted">
                        Don't have an account? <a href="{{ route('register') }}" class="text-warning-emphasis fw-bold text-decoration-none">Create Account</a>
                    </div>
                </form>

                <div class="mt-4 pt-3 border-top text-center">
                    <span class="badge bg-light text-secondary border px-3 py-2">
                        Demo Account: <strong>santi@gmail.com</strong> / <strong>password</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
