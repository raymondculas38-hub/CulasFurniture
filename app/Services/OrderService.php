<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function createOrder(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Your cart is empty.');
            }

            // Validate stock with exclusive row locks to eliminate concurrency race conditions
            foreach ($cartItems as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                if (!$product || $item->quantity > $product->stock) {
                    $name = $product ? $product->name : 'Product';
                    $avail = $product ? $product->stock : 0;
                    throw new \Exception("Insufficient stock for {$name}. Available: {$avail}");
                }
                if (!$product->is_active) {
                    throw new \Exception("{$product->name} is no longer available.");
                }
            }

            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->effective_price;
            });

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => Order::STATUS_PENDING,
                'payment_method' => 'COD',
                'delivery_name' => $data['delivery_name'],
                'delivery_phone' => $data['delivery_phone'],
                'delivery_address' => $data['delivery_address'],
                'delivery_city' => $data['delivery_city'] ?? null,
                'delivery_notes' => $data['delivery_notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->effective_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->product->effective_price,
                ]);
            }

            // Clear cart
            CartItem::where('user_id', $userId)->delete();

            // Notify
            $this->notificationService->notifyNewOrder($order);

            return $order;
        });
    }

    public function updateStatus(Order $order, string $newStatus, ?string $cancelReason = null): Order
    {
        return DB::transaction(function () use ($order, $newStatus, $cancelReason) {
            $validTransitions = [
                Order::STATUS_PENDING => [Order::STATUS_ACCEPTED, Order::STATUS_CANCELLED],
                Order::STATUS_ACCEPTED => [Order::STATUS_PREPARING, Order::STATUS_CANCELLED],
                Order::STATUS_PREPARING => [Order::STATUS_OUT_FOR_DELIVERY],
                Order::STATUS_OUT_FOR_DELIVERY => [Order::STATUS_DELIVERED],
            ];

            if (!isset($validTransitions[$order->status]) || !in_array($newStatus, $validTransitions[$order->status])) {
                throw new \Exception("Cannot transition from {$order->status} to {$newStatus}.");
            }

            $order->status = $newStatus;

            // Set timestamps
            switch ($newStatus) {
                case Order::STATUS_ACCEPTED:
                    $order->accepted_at = now();
                    // Decrement stock on acceptance with exclusive lock
                    foreach ($order->items as $item) {
                        $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                        if (!$product || $product->stock < $item->quantity) {
                            $name = $product ? $product->name : 'Product';
                            throw new \Exception("Insufficient stock for {$name}.");
                        }
                        $product->decrement('stock', $item->quantity);
                    }
                    break;
                case Order::STATUS_PREPARING:
                    $order->preparing_at = now();
                    break;
                case Order::STATUS_OUT_FOR_DELIVERY:
                    $order->out_for_delivery_at = now();
                    break;
                case Order::STATUS_DELIVERED:
                    $order->delivered_at = now();
                    break;
                case Order::STATUS_CANCELLED:
                    $order->cancelled_at = now();
                    $order->cancel_reason = $cancelReason;
                    // Restore stock if order was already accepted
                    if ($order->getOriginal('status') === Order::STATUS_ACCEPTED) {
                        foreach ($order->items as $item) {
                            Product::where('id', $item->product_id)->lockForUpdate()->first()?->increment('stock', $item->quantity);
                        }
                    }
                    break;
            }

            $order->save();

            // Notify user
            $this->notificationService->notifyOrderStatusChange($order);

            // Send review reminder when delivered
            if ($newStatus === Order::STATUS_DELIVERED) {
                $this->notificationService->notifyReviewReminder($order);
            }

            return $order;
        });
    }

    public function cancelOrder(Order $order, ?string $reason = null): Order
    {
        if (!$order->canBeCancelled()) {
            throw new \Exception('This order can no longer be cancelled.');
        }

        return $this->updateStatus($order, Order::STATUS_CANCELLED, $reason ?? 'Cancelled by user');
    }
}
