@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview Dashboard')
@section('page-subtitle', 'Real-time sales, order fulfillment, and inventory analytics')

@section('content')
<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Delivered Sales (COD)</span>
                <h3 class="fw-bold text-dark font-serif mb-0">₱{{ number_format($totalSales, 2) }}</h3>
                <small class="text-success fw-semibold"><i class="bi bi-arrow-up-right"></i> Completed revenue</small>
            </div>
            <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                <i class="bi bi-cash-coin"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Total Orders</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalOrders }}</h3>
                <small class="text-muted">{{ $orderStatuses['pending'] }} pending approval</small>
            </div>
            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-bag-check"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Registered Customers</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalCustomers }}</h3>
                <small class="text-muted">Active user accounts</small>
            </div>
            <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Furniture Catalog</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalProducts }}</h3>
                <small class="text-muted">{{ $lowStockProducts->count() }} low in stock</small>
            </div>
            <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-lamp"></i>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Quick Summary Bar -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-warning text-dark px-2 py-1 mb-2 fw-bold">PENDING</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['pending'] }}</h4>
                <small class="text-muted">Awaiting Action</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'accepted']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-info text-dark px-2 py-1 mb-2 fw-bold">ACCEPTED</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['accepted'] }}</h4>
                <small class="text-muted">Processing</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-primary text-white px-2 py-1 mb-2 fw-bold">PREPARING</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['preparing'] }}</h4>
                <small class="text-muted">In Workshop</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-secondary text-white px-2 py-1 mb-2 fw-bold">EN ROUTE</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['out_for_delivery'] }}</h4>
                <small class="text-muted">Out for Delivery</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-success text-white px-2 py-1 mb-2 fw-bold">DELIVERED</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['delivered'] }}</h4>
                <small class="text-muted">Completed</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="text-decoration-none">
            <div class="p-3 bg-white rounded-3 border text-center shadow-sm hover-lift">
                <span class="badge bg-danger text-white px-2 py-1 mb-2 fw-bold">CANCELLED</span>
                <h4 class="fw-bold text-dark mb-0">{{ $orderStatuses['cancelled'] }}</h4>
                <small class="text-muted">Void Orders</small>
            </div>
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Monthly Revenue Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Delivered Revenue Trend ($)</h5>
                    <small class="text-muted">Calculated strictly from actual delivered COD transactions</small>
                </div>
                <a href="{{ route('admin.analytics') }}" class="btn btn-outline-secondary btn-sm">Deep Analytics</a>
            </div>
            <div style="height: 280px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Best Selling Furniture -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h5 class="fw-bold mb-3">Top Selling Pieces</h5>
            
            @if($bestSellers->isEmpty())
                <div class="text-center py-4 text-muted small">No delivered orders yet.</div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($bestSellers as $bs)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="rounded-2 overflow-hidden border" style="width: 44px; height: 44px; flex-shrink: 0;">
                                    <img src="{{ $bs->main_image }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-0 small text-truncate">{{ $bs->name }}</h6>
                                    <small class="text-muted">₱{{ number_format($bs->price, 2) }}</small>
                                </div>
                            </div>
                            <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">{{ $bs->total_sold }} sold</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0">Recent Orders</h5>
            <small class="text-muted">Live incoming customer orders</small>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-dark btn-sm">View All Orders</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-muted text-uppercase">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total (COD)</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $ro)
                    <tr>
                        <td class="fw-bold text-dark">#{{ $ro->order_number }}</td>
                        <td>
                            <div class="fw-semibold small text-dark">{{ $ro->delivery_name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $ro->delivery_phone }}</div>
                        </td>
                        <td class="small text-muted">{{ $ro->created_at->format('M d, Y H:i') }}</td>
                        <td class="fw-bold text-dark">₱{{ number_format($ro->total, 2) }}</td>
                        <td>
                            <span class="badge badge-status-{{ $ro->status }} px-2 py-1 rounded-pill small text-uppercase fw-semibold">
                                {{ $ro->status_label }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $ro->id) }}" class="btn btn-outline-dark btn-sm py-1 px-2">
                                Manage <i class="bi bi-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No orders placed yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');
    const labels = @json($monthlySales['labels']);
    const sales = @json($monthlySales['sales']);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Delivered Revenue (₱)',
                data: sales.length ? sales : [0, 0, 0, 0, 0, 0],
                borderColor: '#C58940',
                backgroundColor: 'rgba(197, 137, 64, 0.1)',
                borderWidth: 3,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#2C241E',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
