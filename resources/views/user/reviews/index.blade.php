@extends('layouts.app')

@section('title', 'My Furniture Reviews')

@section('content')
<div class="container py-4 my-2">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active">My Reviews</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 font-serif">My Reviews & Ratings</h2>
        </div>
    </div>

    <!-- Pending Reviews Notice (if user has unreviewed items from delivered orders) -->
    @if(count($unreviewedItems) > 0)
        <div class="alert alert-warning border-0 rounded-4 p-4 mb-4 shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-stars fs-4 text-warning"></i>
                <h5 class="fw-bold mb-0 text-dark">You have {{ count($unreviewedItems) }} delivered {{ Str::plural('piece', count($unreviewedItems)) }} awaiting your review!</h5>
            </div>
            <p class="small text-muted mb-3">Your feedback helps fellow homeowners choose the right furniture.</p>
            <div class="row g-3">
                @foreach($unreviewedItems as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="bg-white p-3 rounded-3 border d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <div class="rounded-2 overflow-hidden" style="width: 44px; height: 44px; flex-shrink: 0;">
                                    <img src="{{ $item['product']->main_image }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold small text-dark text-truncate">{{ $item['product']->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Order #{{ $item['order']->order_number }}</div>
                                </div>
                            </div>
                            <a href="{{ route('orders.show', $item['order']->id) }}#reviewSection" class="btn btn-warning btn-sm fw-bold">
                                Rate
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Submitted Reviews List -->
    <h5 class="fw-bold mb-3 font-serif">Submitted Reviews</h5>

    @if($reviews->isEmpty())
        <div class="luxury-card p-5 text-center my-3">
            <div class="p-3 bg-light rounded-circle d-inline-block text-muted mb-3">
                <i class="bi bi-star fs-1"></i>
            </div>
            <h5 class="fw-bold mb-1">No Reviews Written Yet</h5>
            <p class="text-muted small mb-3">You will be able to review furniture after receiving your delivered orders.</p>
            <a href="{{ route('orders.index') }}" class="btn btn-luxury-outline btn-sm">View My Orders</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $rev)
                <div class="col-md-6">
                    <div class="luxury-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 overflow-hidden border" style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <img src="{{ $rev->product->main_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $rev->product->name }}">
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 small">
                                        <a href="{{ route('products.show', $rev->product->slug) }}" class="text-decoration-none text-dark hover-accent">
                                            {{ $rev->product->name }}
                                        </a>
                                    </h6>
                                    <span class="text-muted" style="font-size: 0.75rem;">Order #{{ $rev->order->order_number }}</span>
                                </div>
                            </div>
                            <div class="rating-stars small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rev->rating ? '-fill text-warning' : ' text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <p class="text-dark small flex-grow-1 mb-3">"{{ $rev->comment }}"</p>
                        <div class="text-muted small mt-auto" style="font-size: 0.75rem;">
                            Reviewed on {{ $rev->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-2 d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
