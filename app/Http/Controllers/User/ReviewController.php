<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreReviewRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product.images', 'order'])
            ->latest()
            ->paginate(10);

        // Pending reviewable products from delivered orders
        $deliveredOrders = Order::where('user_id', Auth::id())
            ->where('status', Order::STATUS_DELIVERED)
            ->with('items.product')
            ->get();

        $reviewedProductOrderKeys = Review::where('user_id', Auth::id())
            ->get()
            ->map(fn($r) => $r->order_id . '_' . $r->product_id)
            ->toArray();

        $unreviewedItems = [];
        foreach ($deliveredOrders as $order) {
            foreach ($order->items as $item) {
                $key = $order->id . '_' . $item->product_id;
                if (!in_array($key, $reviewedProductOrderKeys)) {
                    $unreviewedItems[] = [
                        'order' => $order,
                        'product' => $item->product,
                    ];
                }
            }
        }

        return view('user.reviews.index', compact('reviews', 'unreviewedItems'));
    }

    public function store(StoreReviewRequest $request)
    {
        $userId = Auth::id();

        // Enforce authorization: must belong to a DELIVERED order for this user
        $order = Order::where('id', $request->order_id)
            ->where('user_id', $userId)
            ->where('status', Order::STATUS_DELIVERED)
            ->whereHas('items', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            })
            ->first();

        if (!$order) {
            return back()->with('error', 'You can only review products from orders that have been successfully delivered.');
        }

        // Prevent duplicate review submissions
        $existing = Review::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your review! Your rating has been submitted.');
    }
}
