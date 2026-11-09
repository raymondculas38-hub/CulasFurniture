<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case PREPARING = 'preparing';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::PREPARING => 'Preparing',
            self::OUT_FOR_DELIVERY => 'Out for Delivery',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }

    /**
     * Get badge color class for UI.
     */
    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::ACCEPTED => 'info',
            self::PREPARING => 'primary',
            self::OUT_FOR_DELIVERY => 'secondary',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
        };
    }

    /**
     * Allowed state transitions from current status.
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::PENDING => [self::ACCEPTED, self::CANCELLED],
            self::ACCEPTED => [self::PREPARING, self::CANCELLED],
            self::PREPARING => [self::OUT_FOR_DELIVERY],
            self::OUT_FOR_DELIVERY => [self::DELIVERED],
            self::DELIVERED, self::CANCELLED => [],
        };
    }

    /**
     * Check if transition to target status is valid.
     */
    public function canTransitionTo(OrderStatus $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    /**
     * Return array of all string values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

