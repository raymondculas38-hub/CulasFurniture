<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CancelOrderRequest;
use App\Http\Requests\User\StoreOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout()
    {
        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')->with('warning', 'Your cart is empty. Please select furniture to order.');
        }

        // Check stock
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', "{$item->product->name} has only {$item->product->stock} items in stock.");
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->effective_price;
        });

        return view('user.checkout.index', compact('user', 'cartItems', 'subtotal'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated(), Auth::id());
            return redirect()->route('orders.show', $order->id)->with('success', "Order #{$order->order_number} placed successfully! We will deliver it via Cash on Delivery.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['items.product', 'reviews'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Enforce ownership
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.product.images', 'reviews', 'user']);

        return view('user.orders.show', compact('order'));
    }

    public function cancel(CancelOrderRequest $request, Order $order)
    {
        // Enforce ownership
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        try {
            $this->orderService->cancelOrder($order, $request->cancel_reason);
            return back()->with('success', "Order #{$order->order_number} has been cancelled.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
