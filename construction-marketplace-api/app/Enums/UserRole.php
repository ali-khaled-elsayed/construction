<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case SERVICE_PROVIDER = 'service_provider';
    case ADMIN = 'admin';

    /**
     * Get all roles
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $role) => $role->value, self::cases());
    }

    /**
     * Check if role is valid
     *
     * @param string $role
     * @return bool
     */
    public static function isValid(string $role): bool
    {
        return in_array($role, self::values());
    }
}
