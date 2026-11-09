@extends('layouts.app')

@section('title', 'Checkout & Delivery Info')

@section('content')
<div class="container py-4 my-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4 font-serif">Complete Furniture Order</h2>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Left: Delivery Information -->
            <div class="col-lg-7">
                <div class="luxury-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-geo-alt me-2 text-warning"></i> Delivery Information</h5>
                    <p class="text-muted small mb-4">Please provide the exact address where our white-glove logistics team should deliver your items.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Recipient Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="delivery_name" class="form-control bg-light @error('delivery_name') is-invalid @enderror" value="{{ old('delivery_name', $user->name) }}" required placeholder="e.g. John Doe">
                        @error('delivery_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contact Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="delivery_phone" class="form-control bg-light @error('delivery_phone') is-invalid @enderror" value="{{ old('delivery_phone', $user->phone) }}" required placeholder="+1 (555) 000-0000">
                            @error('delivery_phone')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">City / District</label>
                            <input type="text" name="delivery_city" class="form-control bg-light" value="{{ old('delivery_city') }}" placeholder="e.g. Springfield">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Street Address & Unit / Apartment <span class="text-danger">*</span></label>
                        <textarea name="delivery_address" rows="2" class="form-control bg-light @error('delivery_address') is-invalid @enderror" required placeholder="Full street address, building name, unit number...">{{ old('delivery_address', $user->address) }}</textarea>
                        @error('delivery_address')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-semibold">Delivery Instructions / Gate Codes (Optional)</label>
                        <textarea name="delivery_notes" rows="2" class="form-control bg-light" placeholder="e.g. Call 30 minutes before arrival; 3rd floor elevator access">{{ old('delivery_notes') }}</textarea>
                    </div>
                </div>

                <!-- Payment Method (COD Enforced) -->
                <div class="luxury-card p-4">
                    <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-wallet2 me-2 text-warning"></i> Payment Method</h5>
                    
                    <div class="p-3 border border-warning rounded-3 bg-warning bg-opacity-10 d-flex align-items-start gap-3">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="COD" checked disabled>
                        </div>
                        <div>
                            <label class="form-check-label fw-bold text-dark d-block" for="payment_cod">
                                Cash on Delivery (COD)
                            </label>
                            <span class="small text-muted">
                                No online credit card charge today. You will pay directly to our courier representative in cash once the furniture is delivered, unpacked, and verified to your full satisfaction.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="col-lg-5">
                <div class="luxury-card p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-3 font-serif">Order Review ({{ $cartItems->count() }} items)</h5>

                    <div class="d-flex flex-column gap-3 mb-3" style="max-height: 280px; overflow-y: auto;">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-3 pb-2 border-bottom">
                                <div class="rounded-3 overflow-hidden border" style="width: 55px; height: 55px; flex-shrink: 0;">
                                    <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-dark small text-truncate">{{ $item->product->name }}</div>
                                    <div class="text-muted small">Qty: {{ $item->quantity }} × ₱{{ number_format($item->product->effective_price, 2) }}</div>
                                </div>
                                <div class="fw-bold text-dark small">
                                    ₱{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold text-dark">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">White Glove Delivery</span>
                        <span class="text-success fw-bold">FREE</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-dark font-serif fs-5">Total COD Amount</span>
                        <span class="display-6 fw-bold text-dark font-serif">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <button type="submit" class="btn btn-luxury w-100 py-3 fw-bold mb-3">
                        Confirm & Place Order (COD) <i class="bi bi-check2-circle ms-2"></i>
                    </button>

                    <div class="text-center small text-muted">
                        By placing your order, you agree to inspect and accept delivery within standard scheduled hours.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
