@extends('layouts.admin')

@section('title', 'Sales & Store Analytics')
@section('page-title', 'Store Analytics & Performance')
@section('page-subtitle', 'Comprehensive data intelligence calculated dynamically from MySQL records')

@section('content')
<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Total Revenue (COD)</span>
                <h3 class="fw-bold text-dark font-serif mb-0">₱{{ number_format($totalSales, 2) }}</h3>
                <small class="text-success fw-semibold"><i class="bi bi-patch-check"></i> Realized cash sales</small>
            </div>
            <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Total Order Volume</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalOrders }}</h3>
                <small class="text-muted">{{ $orderStatuses['delivered'] }} delivered successfully</small>
            </div>
            <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Customer Accounts</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalCustomers }}</h3>
                <small class="text-muted">Registered shoppers</small>
            </div>
            <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Active Catalog</span>
                <h3 class="fw-bold text-dark font-serif mb-0">{{ $totalProducts }}</h3>
                <small class="text-muted">Furniture items</small>
            </div>
            <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-lamp"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Monthly Revenue Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-1">Delivered Revenue Trend ($)</h5>
            <p class="text-muted small mb-3">Historical monthly revenue from fulfilled deliveries</p>
            <div style="height: 300px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Order Status Distribution Doughnut Chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-1">Order Status Distribution</h5>
            <p class="text-muted small mb-3">All lifetime orders breakdown</p>
            <div style="height: 250px; position: relative;">
                <canvas id="statusDoughnutChart"></canvas>
            </div>
            <div class="d-flex justify-content-around text-center pt-3 border-top mt-auto small">
                <div>
                    <span class="text-success fw-bold d-block">{{ $orderStatuses['delivered'] }}</span>
                    <small class="text-muted">Delivered</small>
                </div>
                <div>
                    <span class="text-warning fw-bold d-block">{{ $orderStatuses['pending'] }}</span>
                    <small class="text-muted">Pending</small>
                </div>
                <div>
                    <span class="text-primary fw-bold d-block">{{ $orderStatuses['accepted'] + $orderStatuses['preparing'] + $orderStatuses['out_for_delivery'] }}</span>
                    <small class="text-muted">In-Flight</small>
                </div>
                <div>
                    <span class="text-danger fw-bold d-block">{{ $orderStatuses['cancelled'] }}</span>
                    <small class="text-muted">Cancelled</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Best-Selling and Most Requested Tables -->
<div class="row g-4 mb-4">
    <!-- Best Selling Furniture Pieces -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-trophy text-warning me-2"></i> Best-Selling Products (By Units Sold)</h5>
            
            @if($bestSellers->isEmpty())
                <div class="text-center py-4 text-muted small">No delivered orders recorded yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th class="text-end">Units Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellers as $bs)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 overflow-hidden border" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                <img src="{{ $bs->main_image }}" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <span class="fw-bold small text-dark">{{ $bs->name }}</span>
                                        </div>
                                    </td>
                                    <td class="small text-muted">{{ $bs->category->name }}</td>
                                    <td class="small fw-semibold text-dark">₱{{ number_format($bs->price, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">{{ $bs->total_sold }} units</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Most Requested Products -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-fire text-danger me-2"></i> Most Requested Furniture (By Orders)</h5>

            @if($mostRequested->isEmpty())
                <div class="text-center py-4 text-muted small">No order items recorded yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light small text-muted text-uppercase">
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                                <th>Rating</th>
                                <th class="text-end">Order Requests</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mostRequested as $mr)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-2 overflow-hidden border" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                <img src="{{ $mr->main_image }}" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <span class="fw-bold small text-dark">{{ $mr->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $mr->stock }} in stock</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1 small">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <span>{{ $mr->average_rating }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold">{{ $mr->order_count }} orders</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Monthly Sales Line Chart
    const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const labels = @json($monthlySales['labels']);
    const sales = @json($monthlySales['sales']);

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Delivered Revenue (₱)',
                data: sales.length ? sales : [0, 0, 0, 0, 0, 0],
                borderColor: '#C58940',
                backgroundColor: 'rgba(197, 137, 64, 0.12)',
                borderWidth: 3,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#2C241E',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Status Doughnut Chart
    const statusCtx = document.getElementById('statusDoughnutChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Delivered', 'Pending', 'In-Flight', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $orderStatuses['delivered'] }},
                    {{ $orderStatuses['pending'] }},
                    {{ $orderStatuses['accepted'] + $orderStatuses['preparing'] + $orderStatuses['out_for_delivery'] }},
                    {{ $orderStatuses['cancelled'] }}
                ],
                backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '68%'
        }
    });
});
</script>
@endpush
