@extends('layouts.app')

@section('title', 'Page Expired (419)')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-5 rounded-4 bg-white shadow-sm border">
                <div class="mb-4 text-warning">
                    <i class="bi bi-clock-history" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">419</h1>
                <h4 class="fw-semibold mb-3">Session Expired</h4>
                <p class="text-muted mb-4">
                    Your security session has expired due to inactivity. Please refresh the page and try submitting the form again.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button onclick="window.location.reload();" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Page
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

