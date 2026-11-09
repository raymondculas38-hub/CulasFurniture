@extends('layouts.app')

@section('title', 'My Order History')

@section('content')
<div class="container py-4 my-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active">Order History</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 font-serif">My Furniture Orders</h2>
        </div>

        <!-- Status Filter Pills -->
        <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
            <a href="{{ route('orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill">All</a>
            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }} rounded-pill">Pending</a>
            <a href="{{ route('orders.index', ['status' => 'accepted']) }}" class="btn btn-sm {{ request('status') == 'accepted' ? 'btn-info text-white' : 'btn-outline-secondary' }} rounded-pill">Accepted</a>
            <a href="{{ route('orders.index', ['status' => 'preparing']) }}" class="btn btn-sm {{ request('status') == 'preparing' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">Preparing</a>
            <a href="{{ route('orders.index', ['status' => 'out_for_delivery']) }}" class="btn btn-sm {{ request('status') == 'out_for_delivery' ? 'btn-secondary text-white' : 'btn-outline-secondary' }} rounded-pill">Out for Delivery</a>
            <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') == 'delivered' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill">Delivered</a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="luxury-card p-5 text-center my-4">
            <div class="p-3 bg-light rounded-circle d-inline-block text-muted mb-3">
                <i class="bi bi-box-seam fs-1"></i>
            </div>
            <h4 class="fw-bold mb-2 font-serif">No Orders Found</h4>
            <p class="text-muted small mb-4">You haven't placed any orders in this status yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-luxury px-4 py-2">
                Browse Furniture Collection <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    @else
        <div class="d-flex flex-column gap-4">
            @foreach($orders as $order)
                <div class="luxury-card p-4">
                    <!-- Order Card Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pb-3 border-bottom mb-3 gap-2">
                        <div>
                            <span class="text-muted small">Order Number:</span>
                            <span class="fw-bold text-dark fs-6 ms-1">#{{ $order->order_number }}</span>
                            <span class="text-muted small ms-2">• Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-status-{{ $order->status }} px-3 py-2 rounded-pill fw-semibold text-uppercase small">
                                {{ $order->status_label }}
                            </span>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-luxury-outline btn-sm">
                                Track & Details <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Items Grid in Order Card -->
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($order->items as $item)
                                    <div class="d-flex align-items-center gap-2 border rounded-3 p-2 bg-light" style="max-width: 260px;">
                                        <div class="rounded-2 overflow-hidden" style="width: 48px; height: 48px; flex-shrink: 0;">
                                            <img src="{{ $item->product->main_image ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=100&q=80' }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product_name }}">
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold text-dark small text-truncate">{{ $item->product_name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Qty: {{ $item->quantity }} • ₱{{ number_format($item->price, 2) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-4 text-md-end pt-3 pt-md-0 border-top border-top-md-0">
                            <div class="text-muted small">Total (COD):</div>
                            <div class="fs-4 fw-bold text-dark font-serif">₱{{ number_format($order->total, 2) }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                Delivery to: {{ Str::limit($order->delivery_address, 30) }}
                            </div>
                        </div>
                    </div>

                    <!-- Action helpers -->
                    @if($order->isDelivered())
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="text-success small fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Delivered on {{ $order->delivered_at?->format('M d, Y') }}</span>
                            <a href="{{ route('orders.show', $order->id) }}#reviewSection" class="btn btn-warning btn-sm fw-bold">
                                <i class="bi bi-star-fill me-1"></i> Rate & Review Purchased Furniture
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4 pt-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
