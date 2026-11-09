@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-4 my-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted">My Orders</a></li>
            <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
        </ol>
    </nav>

    <!-- Header & Order Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold mb-0 font-serif">Order #{{ $order->order_number }}</h2>
                <span class="badge badge-status-{{ $order->status }} px-3 py-1 rounded-pill fw-bold text-uppercase small">
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="text-muted small mb-0">Ordered on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        @if($order->canBeCancelled())
            <div class="mt-3 mt-md-0">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="bi bi-x-circle me-1"></i> Cancel Order
                </button>
            </div>
        @endif
    </div>

    <!-- Live Order Tracking Stepper Timeline -->
    <div class="luxury-card p-4 p-md-5 mb-4">
        <h5 class="fw-bold mb-4 font-serif text-center">Delivery Tracking Status</h5>

        @if($order->status === 'cancelled')
            <div class="alert alert-danger text-center rounded-3 mb-0">
                <i class="bi bi-x-circle-fill fs-4 d-block mb-2"></i>
                <h5 class="fw-bold mb-1">Order Cancelled</h5>
                <p class="small mb-0">
                    This order was cancelled on {{ $order->cancelled_at?->format('M d, Y h:i A') }}.
                    @if($order->cancel_reason)
                        <br><strong>Reason:</strong> {{ $order->cancel_reason }}
                    @endif
                </p>
            </div>
        @else
            @php
                $statuses = ['pending', 'accepted', 'preparing', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
            @endphp

            <div class="order-tracking-stepper">
                <!-- 1. Pending -->
                <div class="step-item {{ $currentIndex >= 0 ? ($currentIndex > 0 ? 'completed' : 'active') : '' }}">
                    <div class="step-icon">
                        <i class="bi {{ $currentIndex > 0 ? 'bi-check-lg' : 'bi-clock-history' }}"></i>
                    </div>
                    <div class="step-title">Order Placed</div>
                    <div class="step-time">{{ $order->created_at->format('M d, H:i') }}</div>
                </div>

                <!-- 2. Accepted -->
                <div class="step-item {{ $currentIndex >= 1 ? ($currentIndex > 1 ? 'completed' : 'active') : '' }}">
                    <div class="step-icon">
                        <i class="bi {{ $currentIndex > 1 ? 'bi-check-lg' : 'bi-check2-circle' }}"></i>
                    </div>
                    <div class="step-title">Order Accepted</div>
                    <div class="step-time">{{ $order->accepted_at ? $order->accepted_at->format('M d, H:i') : 'Pending' }}</div>
                </div>

                <!-- 3. Preparing -->
                <div class="step-item {{ $currentIndex >= 2 ? ($currentIndex > 2 ? 'completed' : 'active') : '' }}">
                    <div class="step-icon">
                        <i class="bi {{ $currentIndex > 2 ? 'bi-check-lg' : 'bi-box-seam' }}"></i>
                    </div>
                    <div class="step-title">Crafting / Packing</div>
                    <div class="step-time">{{ $order->preparing_at ? $order->preparing_at->format('M d, H:i') : 'Pending' }}</div>
                </div>

                <!-- 4. Out for Delivery -->
                <div class="step-item {{ $currentIndex >= 3 ? ($currentIndex > 3 ? 'completed' : 'active') : '' }}">
                    <div class="step-icon">
                        <i class="bi {{ $currentIndex > 3 ? 'bi-check-lg' : 'bi-truck' }}"></i>
                    </div>
                    <div class="step-title">Out for Delivery</div>
                    <div class="step-time">{{ $order->out_for_delivery_at ? $order->out_for_delivery_at->format('M d, H:i') : 'Pending' }}</div>
                </div>

                <!-- 5. Delivered -->
                <div class="step-item {{ $currentIndex >= 4 ? 'completed' : '' }}">
                    <div class="step-icon">
                        <i class="bi {{ $currentIndex >= 4 ? 'bi-house-check-fill' : 'bi-house' }}"></i>
                    </div>
                    <div class="step-title">Delivered</div>
                    <div class="step-time">{{ $order->delivered_at ? $order->delivered_at->format('M d, H:i') : 'Pending' }}</div>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Order Items -->
        <div class="col-lg-8">
            <div class="luxury-card p-4 mb-4">
                <h5 class="fw-bold mb-3 font-serif">Purchased Furniture Pieces</h5>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3 py-2">
                                            <div class="rounded-3 overflow-hidden border shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                                                <img src="{{ $item->product->main_image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80' }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product_name }}">
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1 small">
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark hover-accent">
                                                        {{ $item->product_name }}
                                                    </a>
                                                </h6>
                                                <span class="badge bg-light text-muted border small">{{ $item->product->category->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center small">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center small fw-semibold">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-dark">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-3 border-top mt-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">White-Glove Delivery & Installation</span>
                        <span class="text-success fw-bold">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <span class="fw-bold text-dark font-serif fs-5">Total COD Amount</span>
                        <span class="display-6 fw-bold text-dark font-serif">₱{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Rate & Review Section (Only visible after order is DELIVERED) -->
            @if($order->isDelivered())
                <div id="reviewSection" class="luxury-card p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0 font-serif"><i class="bi bi-star-fill text-warning me-2"></i> Rate & Review Your Delivery</h5>
                        <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-patch-check-fill me-1"></i> Verified Delivery</span>
                    </div>
                    <p class="text-muted small mb-4">You have received this order. Please rate each furniture piece to help other homeowners make informed decisions.</p>

                    <div class="d-flex flex-column gap-4">
                        @foreach($order->items as $item)
                            @php
                                $existingReview = $order->reviews->firstWhere('product_id', $item->product_id);
                            @endphp

                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-2 overflow-hidden border" style="width: 48px; height: 48px; flex-shrink: 0;">
                                        <img src="{{ $item->product->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product_name }}">
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 small">{{ $item->product_name }}</h6>
                                        <span class="text-muted" style="font-size: 0.75rem;">Price: ₱{{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>

                                @if($existingReview)
                                    <!-- Already Reviewed -->
                                    <div class="bg-white p-3 rounded-2 border">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="rating-stars small">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $existingReview->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text-muted" style="font-size: 0.75rem;">Reviewed on {{ $existingReview->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="small text-dark mb-0">"{{ $existingReview->comment }}"</p>
                                    </div>
                                @else
                                    <!-- Review Form -->
                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Rating</label>
                                                <select name="rating" class="form-select form-select-sm bg-white" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ - 5 Stars</option>
                                                    <option value="4">⭐⭐⭐⭐ - 4 Stars</option>
                                                    <option value="3">⭐⭐⭐ - 3 Stars</option>
                                                    <option value="2">⭐⭐ - 2 Stars</option>
                                                    <option value="1">⭐ - 1 Star</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label small fw-semibold">Your Review / Comments</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="comment" class="form-control bg-white" placeholder="Write feedback on comfort, quality, and finish..." required>
                                                    <button type="submit" class="btn btn-luxury">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Recipient & Delivery Information -->
        <div class="col-lg-4">
            <div class="luxury-card p-4 mb-4">
                <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-person-lines-fill me-2 text-warning"></i> Customer Info</h5>
                
                <div class="mb-3">
                    <span class="text-muted small d-block">Recipient Name</span>
                    <span class="fw-bold text-dark small">{{ $order->delivery_name }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Contact Phone</span>
                    <span class="fw-bold text-dark small">{{ $order->delivery_phone }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block">Delivery Address</span>
                    <span class="fw-semibold text-dark small">{{ $order->delivery_address }}</span>
                    @if($order->delivery_city)
                        <span class="text-muted small d-block">{{ $order->delivery_city }}</span>
                    @endif
                </div>

                @if($order->delivery_notes)
                    <div class="mb-0">
                        <span class="text-muted small d-block">Delivery Notes</span>
                        <span class="text-dark small fst-italic">{{ $order->delivery_notes }}</span>
                    </div>
                @endif
            </div>

            <!-- Payment Badge -->
            <div class="luxury-card p-4">
                <h5 class="fw-bold mb-2 font-serif"><i class="bi bi-cash-stack me-2 text-warning"></i> Payment Details</h5>
                <div class="badge bg-warning bg-opacity-25 text-warning-emphasis px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">
                    Cash on Delivery (COD)
                </div>
                <p class="small text-muted mb-0">
                    Pay the total amount in cash to our delivery driver after personal inspection of the furniture pieces.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Confirmation Modal -->
@if($order->canBeCancelled())
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="cancelOrderModalLabel">Cancel Order #{{ $order->order_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted small">Are you sure you wish to cancel this order? Cancellation is only permitted while your order is still in Pending status.</p>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Reason for Cancellation (Optional)</label>
                            <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Changed mind, ordered duplicate, wrong dimensions..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Keep Order</button>
                        <button type="submit" class="btn btn-danger btn-sm px-3">Confirm Cancellation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
