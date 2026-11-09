@extends('layouts.app')

@section('title', 'Furniture Collections & Catalog')

@section('content')
<div class="container py-4 my-2">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Furniture Collections</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 font-serif">Furniture Catalog</h2>
        </div>
        <div class="text-muted small mt-2 mt-md-0">
            Showing <span class="fw-bold text-dark">{{ $products->total() }}</span> handcrafted pieces
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="luxury-card p-4 sticky-top" style="top: 100px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 fs-6"><i class="bi bi-funnel me-1"></i> Filter Products</h5>
                    @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'in_stock', 'sort']))
                        <a href="{{ route('products.index') }}" class="small text-danger text-decoration-none">Reset All</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('products.index') }}">
                    <!-- Search input -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keywords</label>
                        <input type="text" name="search" class="form-control form-control-sm bg-light" placeholder="Search piece..." value="{{ request('search') }}">
                    </div>

                    <!-- Category filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select form-select-sm bg-light">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Price Range (₱)</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm bg-light" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm bg-light" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <!-- In Stock Checkbox -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="in_stock">
                            In Stock Only
                        </label>
                    </div>

                    <!-- Sort by -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort By</label>
                        <select name="sort" class="form-select form-select-sm bg-light">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-luxury w-100 btn-sm py-2">
                        Apply Filters
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->isEmpty())
                <div class="luxury-card p-5 text-center my-4">
                    <div class="p-3 bg-light rounded-circle d-inline-block text-muted mb-3">
                        <i class="bi bi-search fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-1">No Furniture Found</h4>
                    <p class="text-muted small mb-3">We couldn't find any items matching your selected filter criteria.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-luxury btn-sm">Clear All Filters</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-6 col-xl-4">
                            <div class="luxury-card h-100 d-flex flex-column">
                                <div class="product-img-wrapper">
                                    <img src="{{ $product->main_image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $product->name }}">
                                    <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                                        @if($product->is_on_sale)
                                            <span class="badge-sale">Sale</span>
                                        @endif
                                        <span class="badge bg-dark bg-opacity-75 text-white rounded-pill px-2 py-1 small">{{ $product->category->name }}</span>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if($product->stock > 0)
                                            <span class="badge bg-success bg-opacity-90 rounded-pill px-2 py-1 small">{{ $product->stock }} left</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-2 py-1 small">Sold Out</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="d-flex align-items-center gap-1 mb-2">
                                        <div class="rating-stars small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="small text-muted">({{ $product->review_count }})</span>
                                    </div>

                                    <h5 class="fw-bold text-dark mb-1">
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark hover-accent">
                                            {{ $product->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small flex-grow-1 mb-3">
                                        {{ Str::limit($product->short_description ?? $product->description, 75) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                        <div>
                                            @if($product->is_on_sale)
                                                <span class="fs-5 fw-bold text-dark">₱{{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-muted text-decoration-line-through small ms-1">₱{{ number_format($product->price, 2) }}</span>
                                            @else
                                                <span class="fs-5 fw-bold text-dark">₱{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-luxury btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 pt-3 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
