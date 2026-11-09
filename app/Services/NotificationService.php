<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\User;

class NotificationService
{
    public function notifyUser(int $userId, string $type, string $title, string $message, ?int $orderId = null): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'order_id' => $orderId,
        ]);
    }

    public function notifyOrderStatusChange(Order $order): void
    {
        $messages = [
            Order::STATUS_ACCEPTED => [
                'title' => 'Order Accepted',
                'message' => "Your order #{$order->order_number} has been accepted and is being processed.",
            ],
            Order::STATUS_PREPARING => [
                'title' => 'Order Being Prepared',
                'message' => "Your order #{$order->order_number} is now being prepared for delivery.",
            ],
            Order::STATUS_OUT_FOR_DELIVERY => [
                'title' => 'Order Out for Delivery',
                'message' => "Your order #{$order->order_number} is out for delivery. Please be ready to receive it.",
            ],
            Order::STATUS_DELIVERED => [
                'title' => 'Order Delivered',
                'message' => "Your order #{$order->order_number} has been delivered. Thank you for shopping with us! Don't forget to leave a review.",
            ],
            Order::STATUS_CANCELLED => [
                'title' => 'Order Cancelled',
                'message' => "Your order #{$order->order_number} has been cancelled." . ($order->cancel_reason ? " Reason: {$order->cancel_reason}" : ''),
            ],
        ];

        if (isset($messages[$order->status])) {
            $this->notifyUser(
                $order->user_id,
                'order_' . $order->status,
                $messages[$order->status]['title'],
                $messages[$order->status]['message'],
                $order->id
            );
        }
    }

    public function notifyNewOrder(Order $order): void
    {
        // Notify the user about their new order
        $this->notifyUser(
            $order->user_id,
            'order_placed',
            'Order Placed Successfully',
            "Your order #{$order->order_number} has been placed successfully. We will process it shortly.",
            $order->id
        );

        // Notify all admins about the new order
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->notifyUser(
                $admin->id,
                'new_order',
                'New Order Received',
                "A new order #{$order->order_number} has been placed by {$order->user->name}.",
                $order->id
            );
        }
    }

    public function notifyReviewReminder(Order $order): void
    {
        $this->notifyUser(
            $order->user_id,
            'review_reminder',
            'Rate Your Purchase',
            "Your order #{$order->order_number} has been delivered. We'd love to hear your feedback! Please rate and review the products you purchased.",
            $order->id
        );
    }
}
