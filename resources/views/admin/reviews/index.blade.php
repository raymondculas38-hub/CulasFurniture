@extends('layouts.admin')

@section('title', 'Review Moderation')
@section('page-title', 'Customer Ratings & Reviews')
@section('page-subtitle', 'Monitor customer feedback, average scores, and delete inappropriate content')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <!-- Filter bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="d-flex flex-wrap gap-2 flex-grow-1">
            <input type="text" name="search" class="form-control form-control-sm bg-light" style="max-width: 250px;" placeholder="Search reviewer, product, comment..." value="{{ request('search') }}">
            
            <select name="rating" class="form-select form-select-sm bg-light" style="max-width: 150px;">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
            </select>

            <button type="submit" class="btn btn-dark btn-sm">Filter</button>
            @if(request()->anyFilled(['search', 'rating']))
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            @endif
        </form>

        <div class="text-muted small">
            Total Reviews: <strong class="text-dark">{{ $reviews->total() }}</strong>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light small text-muted text-uppercase">
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th style="min-width: 280px;">Review Comment</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-2 overflow-hidden border" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <img src="{{ $rev->product->main_image }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="fw-bold small text-dark">{{ $rev->product->name }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $rev->user->name }}</div>
                            <small class="text-muted">Order #{{ $rev->order->order_number }}</small>
                        </td>
                        <td>
                            <div class="rating-stars small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rev->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <p class="small text-dark mb-0 fst-italic">"{{ $rev->comment }}"</p>
                        </td>
                        <td class="small text-muted">{{ $rev->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light btn-sm text-danger" title="Delete inappropriate review">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No reviews found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 pt-2 d-flex justify-content-center">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
