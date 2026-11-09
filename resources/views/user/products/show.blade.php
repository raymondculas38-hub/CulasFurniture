@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4 my-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Catalog</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Images Gallery -->
        <div class="col-lg-7">
            <div class="luxury-card p-3 mb-3 bg-white">
                <div class="position-relative overflow-hidden rounded-3" style="height: 480px;">
                    <img id="mainProductImage" src="{{ $product->main_image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1000&q=80' }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                    @if($product->is_on_sale)
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge-sale">Special Offer</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thumbnails Carousel -->
            @if($product->images->count() > 0)
                <div class="d-flex gap-3 overflow-x-auto pb-2">
                    <!-- Main image thumbnail -->
                    <div class="border rounded-3 overflow-hidden cursor-pointer thumb-item active shadow-sm" style="width: 90px; height: 90px; flex-shrink: 0;" onclick="changeMainImage('{{ $product->main_image }}', this)">
                        <img src="{{ $product->main_image }}" class="w-100 h-100 object-fit-cover" alt="Main photo">
                    </div>
                    @foreach($product->images as $img)
                        <div class="border rounded-3 overflow-hidden cursor-pointer thumb-item shadow-sm" style="width: 90px; height: 90px; flex-shrink: 0;" onclick="changeMainImage('{{ $img->image_path }}', this)">
                            <img src="{{ $img->image_path }}" class="w-100 h-100 object-fit-cover" alt="Additional view">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Purchase Information -->
        <div class="col-lg-5">
            <div class="ps-lg-2">
                <!-- Category and Rating -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-light text-muted border px-3 py-1 text-uppercase small">{{ $product->category->name }}</span>
                    
                    <div class="d-flex align-items-center gap-1">
                        <div class="rating-stars small">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                        <span class="fw-bold small text-dark">{{ $product->average_rating }}</span>
                        <span class="text-muted small">({{ $product->review_count }} {{ Str::plural('review', $product->review_count) }})</span>
                    </div>
                </div>

                <h1 class="display-6 fw-bold mb-3 text-dark font-serif">{{ $product->name }}</h1>

                <!-- Pricing -->
                <div class="d-flex align-items-baseline gap-3 mb-4 pb-3 border-bottom">
                    @if($product->is_on_sale)
                        <span class="display-5 fw-bold text-dark font-serif">₱{{ number_format($product->sale_price, 2) }}</span>
                        <span class="fs-4 text-muted text-decoration-line-through">₱{{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-danger rounded-pill px-3 py-1 small">Save ₱{{ number_format($product->price - $product->sale_price, 2) }}</span>
                    @else
                        <span class="display-5 fw-bold text-dark font-serif">₱{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Short description -->
                @if($product->short_description)
                    <p class="lead fs-6 text-muted mb-4">{{ $product->short_description }}</p>
                @endif

                <!-- Stock availability -->
                <div class="mb-4">
                    @if($product->stock > 0)
                        <div class="d-flex align-items-center gap-2 text-success fw-semibold small">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>In Stock ({{ $product->stock }} units ready for immediate white-glove dispatch)</span>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2 text-danger fw-semibold small">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Currently Out of Stock</span>
                        </div>
                    @endif
                </div>

                <!-- Add to cart / Order Form -->
                @if($product->stock > 0)
                    @auth
                        @if(Auth::user()->isUser())
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="row g-3 align-items-center mb-3">
                                    <div class="col-auto">
                                        <label for="quantity" class="col-form-label fw-semibold small">Quantity:</label>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQty()">-</button>
                                            <input type="number" id="qtyInput" name="quantity" class="form-control text-center bg-white" value="1" min="1" max="{{ $product->stock }}">
                                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQty({{ $product->stock }})">+</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <button type="submit" class="btn btn-luxury py-3 w-100 fw-bold">
                                        <i class="bi bi-bag-plus me-2"></i> Add to Cart
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="btn btn-accent py-3 w-100 fw-bold">
                                        <i class="bi bi-lightning-fill me-2"></i> Order Now (Cash on Delivery)
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-secondary small">
                                Logged in as Administrator. Sign in with a customer account to place orders.
                            </div>
                        @endif
                    @else
                        <div class="d-flex flex-column gap-2 mb-4">
                            <a href="{{ route('login') }}" class="btn btn-luxury py-3 w-100 fw-bold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Order
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-luxury-outline py-2 w-100 text-center">
                                Create New Account
                            </a>
                        </div>
                    @endauth
                @else
                    <button class="btn btn-secondary py-3 w-100 fw-bold mb-4" disabled>
                        Item Out of Stock
                    </button>
                @endif

                <!-- Payment and Delivery Assurance Box -->
                <div class="luxury-card p-3 bg-light border-0 rounded-3">
                    <div class="d-flex align-items-start gap-3 mb-2">
                        <i class="bi bi-cash-stack text-warning fs-4"></i>
                        <div>
                            <div class="fw-bold small text-dark">Cash on Delivery (COD)</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Pay cash upon delivery after opening and inspecting your furniture.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-shield-check text-success fs-4"></i>
                        <div>
                            <div class="fw-bold small text-dark">100% Satisfaction Guarantee</div>
                            <div class="text-muted" style="font-size: 0.8rem;">Free returns if the piece does not match description or standards.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Full Description and Specifications -->
    <div class="row mt-5 pt-4">
        <div class="col-12">
            <div class="luxury-card p-4 p-md-5">
                <ul class="nav nav-pills mb-4 border-bottom pb-3" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold px-4" data-bs-toggle="pill" data-bs-target="#tab-description">Craftsmanship & Description</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold px-4" data-bs-toggle="pill" data-bs-target="#tab-reviews">Customer Reviews ({{ $product->review_count }})</button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="tab-description">
                        <h4 class="fw-bold mb-3 font-serif">Artisan Craftsmanship & Materials</h4>
                        <div class="text-muted lh-lg mb-4">
                            {!! nl2br(e($product->description)) !!}
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-box me-1 text-warning"></i> Packaging</h6>
                                    <p class="small text-muted mb-0">Reinforced triple-wall wooden crating for flawless zero-damage transport.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-tools me-1 text-warning"></i> Assembly</h6>
                                    <p class="small text-muted mb-0">White-glove courier assembly included at zero additional charge.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded-3">
                                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-award me-1 text-warning"></i> Warranty</h6>
                                    <p class="small text-muted mb-0">10-Year comprehensive frame and joinery structural warranty.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="tab-reviews">
                        <div class="row g-4">
                            <!-- Left: Rating Summary -->
                            <div class="col-md-4 text-center border-end">
                                <div class="display-3 fw-bold text-dark font-serif">{{ $product->average_rating }}</div>
                                <div class="rating-stars fs-5 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-muted small">Based on {{ $product->review_count }} verified {{ Str::plural('review', $product->review_count) }}</div>

                                <!-- Review Eligibility CTA -->
                                @if($canReview && $eligibleOrderId)
                                    <div class="mt-4 p-3 bg-warning bg-opacity-10 border border-warning rounded-3 text-start">
                                        <div class="fw-bold small text-dark mb-1"><i class="bi bi-patch-check-fill text-warning me-1"></i> You received this item!</div>
                                        <p class="small text-muted mb-2">Share your experience with future homeowners.</p>
                                        <button class="btn btn-warning btn-sm w-100 fw-bold" data-bs-toggle="collapse" data-bs-target="#reviewFormCollapse">
                                            Write a Review
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Reviews List & Write Review Form -->
                            <div class="col-md-8">
                                @if($canReview && $eligibleOrderId)
                                    <div class="collapse mb-4" id="reviewFormCollapse">
                                        <div class="p-4 bg-light rounded-3 border">
                                            <h5 class="fw-bold mb-3">Leave a Verified Review</h5>
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="order_id" value="{{ $eligibleOrderId }}">

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Rating (1 to 5 Stars)</label>
                                                    <select name="rating" class="form-select" required>
                                                        <option value="5">⭐⭐⭐⭐⭐ - 5 Stars (Exceptional)</option>
                                                        <option value="4">⭐⭐⭐⭐ - 4 Stars (Very Good)</option>
                                                        <option value="3">⭐⭐⭐ - 3 Stars (Average)</option>
                                                        <option value="2">⭐⭐ - 2 Stars (Below Expectation)</option>
                                                        <option value="1">⭐ - 1 Star (Poor)</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Your Feedback / Comments</label>
                                                    <textarea name="comment" class="form-control" rows="3" placeholder="Describe the quality, comfort, finish, and delivery experience..." required></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-luxury btn-sm px-4">
                                                    Submit Review
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <!-- Reviews List -->
                                <div class="d-flex flex-column gap-3">
                                    @forelse($product->reviews as $review)
                                        <div class="p-3 border rounded-3 bg-white">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="fw-bold text-dark small">{{ $review->user->name }}</span>
                                                    <span class="badge bg-success bg-opacity-10 text-success ms-2 small" style="font-size: 0.7rem;"><i class="bi bi-check-circle me-1"></i> Verified Purchaser</span>
                                                </div>
                                                <div class="rating-stars small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-2">{{ $review->comment }}</p>
                                            <div class="text-muted" style="font-size: 0.75rem;">Reviewed on {{ $review->created_at->format('M d, Y') }}</div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted small">
                                            <i class="bi bi-chat-square-text fs-2 d-block mb-2 text-secondary"></i>
                                            No reviews yet. Verified customers who receive this item will have the opportunity to review it.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5 pt-4">
            <h3 class="fw-bold mb-4 font-serif">Complementary Pieces</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $rel)
                    <div class="col-6 col-md-3">
                        <div class="luxury-card h-100 d-flex flex-column">
                            <div class="product-img-wrapper" style="padding-top: 70%;">
                                <img src="{{ $rel->main_image }}" alt="{{ $rel->name }}">
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 small">
                                    <a href="{{ route('products.show', $rel->slug) }}" class="text-decoration-none text-dark hover-accent">
                                        {{ $rel->name }}
                                    </a>
                                </h6>
                                <div class="text-dark fw-bold small mt-auto">₱{{ number_format($rel->effective_price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function changeMainImage(url, el) {
    document.getElementById('mainProductImage').src = url;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('border-warning', 'border-2'));
    el.classList.add('border-warning', 'border-2');
}

function increaseQty(maxStock) {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) || 1;
    if (val < maxStock) {
        input.value = val + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('qtyInput');
    let val = parseInt(input.value) || 1;
    if (val > 1) {
        input.value = val - 1;
    }
}
</script>
@endpush
