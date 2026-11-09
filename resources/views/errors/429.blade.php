@extends('layouts.app')

@section('title', 'Too Many Requests (429)')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-5 rounded-4 bg-white shadow-sm border">
                <div class="mb-4 text-danger">
                    <i class="bi bi-speedometer2" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">429</h1>
                <h4 class="fw-semibold mb-3">Too Many Requests</h4>
                <p class="text-muted mb-4">
                    Our automated security shield detected too many requests in a short period of time. Rate limiting is active to protect Santi Furniture systems against abuse.
                </p>
                <div class="alert alert-warning py-2 mb-4 small">
                    <i class="bi bi-hourglass-split me-1"></i> Please pause for 60 seconds before retrying your action.
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <button onclick="window.location.reload();" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-arrow-clockwise me-1"></i> Retry
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

