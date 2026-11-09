@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4 my-2">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active">Shopping Cart</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 font-serif">Your Furniture Cart</h2>
        </div>
        <div class="text-muted small">
            <span>{{ $cartItems->count() }} unique {{ Str::plural('piece', $cartItems->count()) }}</span>
        </div>
    </div>

    @if($cartItems->isEmpty())
        <div class="luxury-card p-5 text-center my-4">
            <div class="p-3 bg-light rounded-circle d-inline-block text-muted mb-3">
                <i class="bi bi-bag-x fs-1"></i>
            </div>
            <h4 class="fw-bold mb-2 font-serif">Your Cart is Empty</h4>
            <p class="text-muted small mb-4">You haven't selected any furniture yet. Explore our handcrafted collections to furnish your dream room.</p>
            <a href="{{ route('products.index') }}" class="btn btn-luxury px-4 py-2">
                Browse Collections <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @else
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="luxury-card p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="border-bottom text-muted small text-uppercase">
                                <tr>
                                    <th scope="col" style="min-width: 250px;">Furniture Item</th>
                                    <th scope="col" class="text-center">Price</th>
                                    <th scope="col" class="text-center" style="width: 140px;">Quantity</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex align-items-center gap-3 py-2">
                                                <div class="rounded-3 overflow-hidden border shadow-sm" style="width: 70px; height: 70px; flex-shrink: 0;">
                                                    <img src="{{ $item->product->main_image }}" alt="{{ $item->product->name }}" class="w-100 h-100 object-fit-cover">
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1 small">
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark hover-accent">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </h6>
                                                    <span class="badge bg-light text-muted border small">{{ $item->product->category->name }}</span>
                                                    @if($item->quantity > $item->product->stock)
                                                        <span class="text-danger small d-block mt-1">Exceeds stock! Max: {{ $item->product->stock }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-semibold small text-dark">
                                            ₱{{ number_format($item->product->effective_price, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm text-center bg-light" style="width: 60px;" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ₱{{ number_format($item->subtotal, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove piece">
                                                    <i class="bi bi-trash fs-6"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 mt-2">
                        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
                        </a>

                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none small p-0" onclick="return confirm('Clear entire cart?')">
                                <i class="bi bi-x-circle me-1"></i> Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary & Checkout Proceed -->
            <div class="col-lg-4">
                <div class="luxury-card p-4">
                    <h5 class="fw-bold mb-3 font-serif">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold text-dark">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">White-Glove Delivery</span>
                        <span class="text-success fw-bold">FREE</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Payment Method</span>
                        <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">Cash on Delivery (COD)</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-dark fs-5 font-serif">Total Due</span>
                        <span class="display-6 fw-bold text-dark font-serif">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-luxury w-100 py-3 fw-bold mb-3">
                        Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <div class="text-center small text-muted">
                        <i class="bi bi-shield-check text-success me-1"></i> Pay cash upon door delivery & inspection.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
