@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-4 my-2">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 font-serif">Order Alerts & Notifications</h2>
        </div>

        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-check2-all me-1"></i> Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="luxury-card p-5 text-center my-3">
            <div class="p-3 bg-light rounded-circle d-inline-block text-muted mb-3">
                <i class="bi bi-bell-slash fs-1"></i>
            </div>
            <h5 class="fw-bold mb-1">No Notifications Yet</h5>
            <p class="text-muted small mb-0">You will receive live notifications when your order status updates or when you are invited to review delivered items.</p>
        </div>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach($notifications as $notif)
                <div class="luxury-card p-3 p-md-4 {{ !$notif->is_read ? 'border-warning bg-warning bg-opacity-10' : '' }}">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="p-2 rounded-circle {{ !$notif->is_read ? 'bg-warning text-dark' : 'bg-light text-muted' }}" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-box-seam-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold text-dark mb-0">{{ $notif->title }}</h6>
                                    @if(!$notif->is_read)
                                        <span class="badge bg-warning text-dark small" style="font-size: 0.65rem;">NEW</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-2">{{ $notif->message }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</span>
                                    @if($notif->order_id)
                                        <a href="{{ route('orders.show', $notif->order_id) }}" class="small text-decoration-none fw-semibold text-warning-emphasis">
                                            View Order <i class="bi bi-arrow-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!$notif->is_read)
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted p-0" title="Mark as read">
                                    <i class="bi bi-check2 fs-5"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-2 d-flex justify-content-center">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
