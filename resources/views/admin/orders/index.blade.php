@extends('layouts.admin')

@section('title', 'Order Management')
@section('page-title', 'Customer Orders & Deliveries')
@section('page-subtitle', 'Track, verify, and advance customer orders across the delivery lifecycle')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <!-- Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex flex-wrap gap-2 flex-grow-1">
            <input type="text" name="search" class="form-control form-control-sm bg-light" style="max-width: 250px;" placeholder="Search #Order, Name, Phone..." value="{{ request('search') }}">
            
            <select name="status" class="form-select form-select-sm bg-light" style="max-width: 180px;">
                <option value="">All Statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-dark btn-sm">Filter</button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>

        <div class="text-muted small">
            Total Orders: <strong class="text-dark">{{ $orders->total() }}</strong>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-muted text-uppercase">
                <tr>
                    <th>Order #</th>
                    <th>Customer / Recipient</th>
                    <th>Date Placed</th>
                    <th>Items</th>
                    <th>Amount (COD)</th>
                    <th>Status</th>
                    <th class="text-end">Manage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-dark">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-dark text-decoration-none hover-accent">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold small text-dark">{{ $order->delivery_name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $order->delivery_phone }}</div>
                        </td>
                        <td class="small text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $order->items->count() }} item(s)</span>
                        </td>
                        <td class="fw-bold text-dark font-serif">
                            ₱{{ number_format($order->total, 2) }}
                        </td>
                        <td>
                            <span class="badge badge-status-{{ $order->status }} px-3 py-1 rounded-pill small text-uppercase fw-semibold">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-luxury btn-sm py-1 px-3">
                                View & Update <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No orders match criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-2 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
