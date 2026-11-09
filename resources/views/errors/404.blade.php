@extends('layouts.app')

@section('title', 'Page Not Found (404)')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-5 rounded-4 bg-white shadow-sm border">
                <div class="mb-4 text-muted">
                    <i class="bi bi-compass" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">404</h1>
                <h4 class="fw-semibold mb-3">Furniture Piece Not Found</h4>
                <p class="text-muted mb-4">
                    The page or catalogue item you are looking for may have been relocated, discontinued, or never existed.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-grid me-1"></i> Browse Catalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

