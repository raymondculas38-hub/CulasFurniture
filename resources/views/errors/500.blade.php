@extends('layouts.app')

@section('title', 'Server Error (500)')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-5 rounded-4 bg-white shadow-sm border">
                <div class="mb-4 text-danger">
                    <i class="bi bi-exclamation-triangle" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">500</h1>
                <h4 class="fw-semibold mb-3">Internal Server Error</h4>
                <p class="text-muted mb-4">
                    An unexpected condition occurred on the server. Our technical team has been logged of this issue. Please try again shortly.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

