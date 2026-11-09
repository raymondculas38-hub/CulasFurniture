@extends('layouts.admin')

@section('title', 'Customer: ' . $user->name)
@section('page-title', 'Customer Profile: ' . $user->name)
@section('page-subtitle', 'Detailed view of customer account, purchase history, and reviews')

@section('content')
<div class="row g-4">
    <!-- User Bio Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="text-center mb-4">
                <div class="rounded-circle bg-warning bg-opacity-25 text-warning-emphasis fw-bold d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 1.75rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <span class="badge {{ $user->isAdmin() ? 'bg-dark text-warning' : 'bg-light text-muted border' }} mt-1">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <ul class="list-unstyled small d-flex flex-column gap-3 mb-0">
                <li>
                    <span class="text-muted d-block">Email Address</span>
                    <strong class="text-dark">{{ $user->email }}</strong>
                </li>
                <li>
                    <span class="text-muted d-block">Phone Number</span>
                    <strong class="text-dark">{{ $user->phone ?? 'Not provided' }}</strong>
                </li>
                <li>
                    <span class="text-muted d-block">Default Address</span>
                    <span class="text-dark">{{ $user->address ?? 'Not provided' }}</span>
                </li>
                <li>
                    <span class="text-muted d-block">Member Since</span>
                    <span class="text-dark">{{ $user->created_at->format('F j, Y') }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- User Orders & Reviews -->
    <div class="col-lg-8">
        <!-- Order History -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3">Order History ({{ $user->orders->count() }})</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light small text-muted text-uppercase">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total (COD)</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $o)
                            <tr>
                                <td class="fw-bold text-dark">#{{ $o->order_number }}</td>
                                <td class="small text-muted">{{ $o->created_at->format('M d, Y') }}</td>
                                <td>{{ $o->items->count() }} item(s)</td>
                                <td class="fw-bold text-dark font-serif">₱{{ number_format($o->total, 2) }}</td>
                                <td>
                                    <span class="badge badge-status-{{ $o->status }} px-2 py-1 rounded-pill small text-uppercase fw-semibold">
                                        {{ $o->status_label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-outline-dark btn-sm py-1 px-2">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found for this customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Reviews -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3">Customer Reviews ({{ $user->reviews->count() }})</h5>

            <div class="d-flex flex-column gap-3">
                @forelse($user->reviews as $ur)
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small text-dark">{{ $ur->product->name }}</span>
                            <div class="rating-stars small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $ur->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="small text-dark mb-1">"{{ $ur->comment }}"</p>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ $ur->created_at->format('M d, Y') }}</small>
                    </div>
                @empty
                    <div class="text-muted small text-center py-3">No reviews submitted yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
