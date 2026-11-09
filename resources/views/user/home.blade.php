@extends('layouts.app')

@section('title', 'Handcrafted Architectural Furniture')

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden py-5 my-lg-4" style="background: linear-gradient(135deg, #F8F5F0 0%, #EFE9E0 100%); border-radius: var(--radius-lg); margin: 0 1rem;">
    <div class="container py-4 py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-warning bg-opacity-25 text-warning-emphasis px-3 py-2 rounded-pill small fw-bold mb-3 d-inline-flex align-items-center gap-1">
                    <i class="bi bi-stars"></i> 2026 LUXURY LIVING COLLECTION
                </span>
                <h1 class="display-4 fw-bold mb-3 text-dark font-serif" style="line-height: 1.15;">
                    Timeless design, handcrafted for living gracefully.
                </h1>
                <p class="lead text-muted mb-4 fs-6 pe-lg-4">
                    Explore sculpted silhouettes, sustainable solid woods, and premium upholstery tailored to elevate your sanctuary. Enjoy risk-free ordering with Cash on Delivery nationwide.
                </p>
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="{{ route('products.index') }}" class="btn btn-luxury px-4 py-3">
                        Explore Collection <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#featured" class="btn btn-luxury-outline px-4 py-3">
                        Featured Pieces
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="row g-3 mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-dark font-serif">100%</div>
                        <div class="small text-muted">Solid Hardwood</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-dark font-serif">COD</div>
                        <div class="small text-muted">Pay at Doorstep</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-4 text-dark font-serif">10-Yr</div>
                        <div class="small text-muted">Frame Warranty</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="luxury-card overflow-hidden shadow-elevated p-2 bg-white" style="border-radius: var(--radius-xl);">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1000&q=80" alt="Hero Modern Lounge Sofa" class="img-fluid rounded-4 w-100" style="height: 440px; object-fit: cover;">
                    </div>
                    <!-- Floating highlight tag -->
                    <div class="position-absolute bottom-0 start-0 m-4 bg-white p-3 rounded-4 shadow-lg d-flex align-items-center gap-3 border" style="max-width: 260px;">
                        <div class="p-2 bg-warning bg-opacity-25 rounded-3 text-warning">
                            <i class="bi bi-award-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="fw-bold small text-dark">Modern Lounge Sofa</div>
                            <div class="text-muted small">From ₱1,149.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Showcase -->
<section class="py-5">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="text-uppercase small fw-bold text-warning letter-spacing-1">Curated Spaces</span>
                <h2 class="fw-bold mb-0">Browse by Category</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-decoration-none fw-semibold text-dark hover-accent">
                All Collections <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="luxury-card text-center p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                            <div class="rounded-circle overflow-hidden mb-3 border shadow-sm" style="width: 80px; height: 80px;">
                                <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=200&q=80' }}" alt="{{ $category->name }}" class="w-100 h-100 object-fit-cover">
                            </div>
                            <h6 class="fw-bold text-dark mb-1 small">{{ $category->name }}</h6>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $category->products_count }} Items</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Furniture -->
<section id="featured" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5 max-w-lg mx-auto">
            <span class="text-uppercase small fw-bold text-warning letter-spacing-1">Iconic Creations</span>
            <h2 class="fw-bold mb-2">Featured Masterpieces</h2>
            <p class="text-muted small">Each piece is hand-finished with artisan care, ensuring unique texture and lifetime durability.</p>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-4">
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
                                    <span class="badge bg-success bg-opacity-90 rounded-pill px-2 py-1 small"><i class="bi bi-check-circle me-1"></i> In Stock</span>
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
                                    View Piece
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section id="about" class="py-5" style="background-color: #FAF6F0;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-uppercase small fw-bold text-warning letter-spacing-1">The Santi Difference</span>
            <h2 class="fw-bold mb-2">Why Discerning Homeowners Choose Us</h2>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-6 col-lg-3">
                <div class="luxury-card p-4 h-100">
                    <div class="stat-icon-wrapper bg-warning bg-opacity-25 text-warning-emphasis mx-auto mb-3">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Cash on Delivery</h5>
                    <p class="text-muted small mb-0">Inspect your furniture upon arrival and pay cash securely at your front door.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="luxury-card p-4 h-100">
                    <div class="stat-icon-wrapper bg-success bg-opacity-25 text-success mx-auto mb-3">
                        <i class="bi bi-tree fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Sustainably Sourced</h5>
                    <p class="text-muted small mb-0">Kiln-dried solid oaks and walnuts harvested exclusively from certified sustainable forests.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="luxury-card p-4 h-100">
                    <div class="stat-icon-wrapper bg-primary bg-opacity-25 text-primary mx-auto mb-3">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">White-Glove Delivery</h5>
                    <p class="text-muted small mb-0">Our professional delivery team unboxes, inspects, and places your items in your desired room.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="luxury-card p-4 h-100">
                    <div class="stat-icon-wrapper bg-info bg-opacity-25 text-info mx-auto mb-3">
                        <i class="bi bi-patch-check fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">10-Year Warranty</h5>
                    <p class="text-muted small mb-0">Structural steel and hardwood frames guaranteed against manufacturing defects for 10 years.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="text-uppercase small fw-bold text-warning letter-spacing-1">Verified Experiences</span>
            <h2 class="fw-bold mb-2">What Our Customers Say</h2>
            <p class="text-muted small">Reviews submitted exclusively by verified customers after receiving their deliveries.</p>
        </div>

        <div class="row g-4">
            @forelse($latestReviews as $rev)
                <div class="col-md-4">
                    <div class="luxury-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rev->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                            <span class="badge bg-light text-muted small"><i class="bi bi-patch-check-fill text-success me-1"></i> Verified Purchase</span>
                        </div>
                        <p class="fst-italic text-dark small flex-grow-1 mb-3">"{{ $rev->comment }}"</p>
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                            <div>
                                <div class="fw-bold small text-dark">{{ $rev->user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Purchased: {{ $rev->product->name }}</div>
                            </div>
                            <div class="text-muted small">{{ $rev->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-4">
                    <p>No customer reviews yet. Be the first to order and share your feedback!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5">
    <div class="container">
        <div class="p-5 rounded-4 text-white text-center position-relative overflow-hidden" style="background: radial-gradient(circle at center, #3A2F28, #1E1916);">
            <h2 class="display-6 fw-bold mb-3 font-serif">Redefine Your Living Space Today</h2>
            <p class="lead text-white-50 mb-4 max-w-lg mx-auto fs-6">
                Discover artisan-built furniture designed to endure generations. Place your order in under 2 minutes with zero upfront payment.
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-5 py-3 fw-bold text-dark shadow">
                Browse Complete Catalog <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endsection
