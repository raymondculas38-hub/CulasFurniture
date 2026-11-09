<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'Customer',
        };
    }

    /**
     * Return array of all string values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

