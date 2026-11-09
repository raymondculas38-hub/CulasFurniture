@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_number)
@section('page-title', 'Order Management: #' . $order->order_number)
@section('page-subtitle', 'Review order items, customer delivery details, and advance status')

@section('content')
<div class="row g-4">
    <!-- Left Column: Status Workflow Control & Items -->
    <div class="col-lg-8">
        <!-- Status Transition Controller -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Delivery Status Workflow</h5>
                <span class="badge badge-status-{{ $order->status }} px-3 py-2 rounded-pill fw-bold text-uppercase">
                    Current: {{ $order->status_label }}
                </span>
            </div>

            <!-- Workflow Progress Tracker -->
            @php
                $statuses = ['pending', 'accepted', 'preparing', 'out_for_delivery', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
            @endphp

            @if($order->status !== 'cancelled')
                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="d-flex justify-content-between text-center small fw-semibold text-muted">
                        <div class="{{ $currentIndex >= 0 ? 'text-dark fw-bold' : '' }}">1. Pending</div>
                        <div class="{{ $currentIndex >= 1 ? 'text-dark fw-bold' : '' }}">2. Accepted</div>
                        <div class="{{ $currentIndex >= 2 ? 'text-dark fw-bold' : '' }}">3. Preparing</div>
                        <div class="{{ $currentIndex >= 3 ? 'text-dark fw-bold' : '' }}">4. Out for Delivery</div>
                        <div class="{{ $currentIndex >= 4 ? 'text-success fw-bold' : '' }}">5. Delivered</div>
                    </div>
                </div>
            @endif

            <!-- Quick Action Buttons based on current state -->
            @if($order->status === 'pending')
                <div class="alert alert-warning border-0 rounded-3 mb-3 small">
                    <i class="bi bi-info-circle me-1"></i> New order awaiting your review. Accepting will allocate stock and notify the customer.
                </div>
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-check-lg me-1"></i> Accept & Confirm Order
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectOrderModal">
                        <i class="bi bi-x-lg me-1"></i> Reject Order
                    </button>
                </div>
            @elseif($order->status === 'accepted')
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="preparing">
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-box-seam me-1"></i> Mark as Preparing / Crafting
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectOrderModal">
                        Cancel Order
                    </button>
                </div>
            @elseif($order->status === 'preparing')
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="out_for_delivery">
                    <button type="submit" class="btn btn-info text-white fw-bold px-4">
                        <i class="bi bi-truck me-1"></i> Dispatch (Out for Delivery)
                    </button>
                </form>
            @elseif($order->status === 'out_for_delivery')
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="bi bi-house-check me-1"></i> Mark as Delivered (COD Collected)
                    </button>
                </form>
            @elseif($order->status === 'delivered')
                <div class="alert alert-success border-0 rounded-3 mb-0 small">
                    <i class="bi bi-check-circle-fill me-1"></i> Order delivered on {{ $order->delivered_at?->format('M d, Y h:i A') }}. COD payment received.
                </div>
            @elseif($order->status === 'cancelled')
                <div class="alert alert-danger border-0 rounded-3 mb-0 small">
                    <i class="bi bi-x-circle-fill me-1"></i> Order cancelled on {{ $order->cancelled_at?->format('M d, Y h:i A') }}.
                    @if($order->cancel_reason)
                        <br><strong>Reason:</strong> {{ $order->cancel_reason }}
                    @endif
                </div>
            @endif
        </div>

        <!-- Ordered Items Table -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3">Order Items ({{ $order->items->count() }})</h5>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light small text-muted text-uppercase">
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3 py-1">
                                        <div class="rounded-2 overflow-hidden border" style="width: 50px; height: 50px; flex-shrink: 0;">
                                            <img src="{{ $item->product->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product_name }}">
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 small">{{ $item->product_name }}</h6>
                                            <small class="text-muted">{{ $item->product->category->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center small">₱{{ number_format($item->price, 2) }}</td>
                                <td class="text-center small fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold font-serif">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-top mt-3 text-end">
                <div class="text-muted small">Total Due on Delivery (COD):</div>
                <div class="display-6 fw-bold text-dark font-serif">₱{{ number_format($order->total, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Customer Info & Timeline -->
    <div class="col-lg-4">
        <!-- Customer & Delivery Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3">Customer & Delivery Info</h5>

            <div class="mb-3">
                <small class="text-muted d-block">Recipient Name</small>
                <div class="fw-bold text-dark">{{ $order->delivery_name }}</div>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Contact Phone</small>
                <div class="fw-bold text-dark">{{ $order->delivery_phone }}</div>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block">Delivery Address</small>
                <div class="small text-dark">{{ $order->delivery_address }}</div>
                @if($order->delivery_city)
                    <div class="small text-muted">{{ $order->delivery_city }}</div>
                @endif
            </div>

            @if($order->delivery_notes)
                <div class="mb-3">
                    <small class="text-muted d-block">Special Instructions</small>
                    <div class="small text-dark fst-italic p-2 bg-light rounded-2">{{ $order->delivery_notes }}</div>
                </div>
            @endif

            <hr>

            <div>
                <small class="text-muted d-block">Account Holder</small>
                <div class="fw-semibold small text-dark">{{ $order->user->name }} ({{ $order->user->email }})</div>
                <a href="{{ route('admin.users.show', $order->user_id) }}" class="small text-primary text-decoration-none">View Customer Profile <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <!-- Order Timestamps Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3">Lifecycle Timestamps</h5>
            <ul class="list-unstyled small text-muted mb-0 d-flex flex-column gap-2">
                <li><strong>Placed:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</li>
                <li><strong>Accepted:</strong> {{ $order->accepted_at ? $order->accepted_at->format('M d, Y h:i A') : '—' }}</li>
                <li><strong>Preparing:</strong> {{ $order->preparing_at ? $order->preparing_at->format('M d, Y h:i A') : '—' }}</li>
                <li><strong>Out for Delivery:</strong> {{ $order->out_for_delivery_at ? $order->out_for_delivery_at->format('M d, Y h:i A') : '—' }}</li>
                <li><strong>Delivered:</strong> {{ $order->delivered_at ? $order->delivered_at->format('M d, Y h:i A') : '—' }}</li>
                @if($order->cancelled_at)
                    <li class="text-danger"><strong>Cancelled:</strong> {{ $order->cancelled_at->format('M d, Y h:i A') }}</li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Reject / Cancel Modal -->
<div class="modal fade" id="rejectOrderModal" tabindex="-1" aria-labelledby="rejectOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="rejectOrderModalLabel">Reject / Cancel Order #{{ $order->order_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <div class="modal-body">
                    <p class="text-muted small">Please provide the reason for cancelling this order. This will be sent to the customer via notification.</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Reason for Rejection</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" required placeholder="Out of stock, invalid delivery address, delivery area not covered..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-sm px-3">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
