@extends('layouts.app')

@section('title', 'My Shopping Analytics')

@section('content')
<div class="container py-4 my-2">
    <div class="mb-4 pb-2 border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active">Shopping Analytics</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0 font-serif">My Shopping Insights</h2>
        <span class="text-muted small">Real-time breakdown of your orders, deliveries, and furniture investments</span>
    </div>

    <!-- 4 KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <span class="text-muted small d-block mb-1">Total Orders</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <span class="text-muted small d-block mb-1">Delivered & Complete</span>
                    <h3 class="fw-bold text-success mb-0">{{ $completedOrders }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <span class="text-muted small d-block mb-1">Active / Pending</span>
                    <h3 class="fw-bold text-warning mb-0">{{ $pendingOrders }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div>
                    <span class="text-muted small d-block mb-1">Total Spent (COD)</span>
                    <h3 class="fw-bold text-dark font-serif mb-0">₱{{ number_format($totalSpent, 2) }}</h3>
                </div>
                <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Most Purchased Furniture -->
        <div class="col-lg-6">
            <div class="luxury-card p-4 h-100">
                <h5 class="fw-bold mb-3 font-serif"><i class="bi bi-trophy me-2 text-warning"></i> Frequently Purchased Items</h5>
                
                @if($mostPurchased->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-bag-check fs-2 d-block mb-2 text-secondary"></i>
                        No completed deliveries yet. Once orders are delivered, your most frequent furniture purchases will display here.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($mostPurchased as $p)
                            <div class="d-flex align-items-center justify-content-between p-2 border rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-2 overflow-hidden border" style="width: 50px; height: 50px; flex-shrink: 0;">
                                        <img src="{{ $p->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $p->name }}">
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 small">
                                            <a href="{{ route('products.show', $p->slug) }}" class="text-decoration-none text-dark hover-accent">
                                                {{ $p->name }}
                                            </a>
                                        </h6>
                                        <span class="text-muted" style="font-size: 0.75rem;">Price: ₱{{ number_format($p->effective_price, 2) }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">{{ $p->total_purchased }} unit(s)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Orders Summary -->
        <div class="col-lg-6">
            <div class="luxury-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 font-serif"><i class="bi bi-clock-history me-2 text-warning"></i> Recent Orders</h5>
                    <a href="{{ route('orders.index') }}" class="small text-decoration-none text-warning-emphasis fw-semibold">View All</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-4 text-muted small">
                        No orders recorded yet.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentOrders as $ro)
                            <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-bold text-dark small">#{{ $ro->order_number }}</span>
                                        <span class="badge badge-status-{{ $ro->status }} px-2 py-0 small text-uppercase" style="font-size: 0.7rem;">{{ $ro->status_label }}</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $ro->created_at->format('M d, Y') }} • {{ $ro->items->count() }} item(s)</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark small font-serif">₱{{ number_format($ro->total, 2) }}</div>
                                    <a href="{{ route('orders.show', $ro->id) }}" class="small text-decoration-none text-muted" style="font-size: 0.75rem;">Track <i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
