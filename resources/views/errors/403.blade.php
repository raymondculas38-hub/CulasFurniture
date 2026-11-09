@extends('layouts.app')

@section('title', 'Access Denied (403)')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-5 rounded-4 bg-white shadow-sm border">
                <div class="mb-4 text-warning">
                    <i class="bi bi-shield-lock-fill" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">403</h1>
                <h4 class="fw-semibold mb-3">Access Denied</h4>
                <p class="text-muted mb-4">
                    You do not have the required permissions to access this area. If you believe this is an error, please contact system administration.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

