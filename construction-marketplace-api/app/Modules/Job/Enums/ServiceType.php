<?php

namespace App\Modules\Job\Enums;

enum ServiceType: string
{
    case SPECIALIST = 'specialist';
    case SERVICE_PROVIDER = 'service_provider';

    /**
     * Get all service types
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }

    /**
     * Check if service type is valid
     *
     * @param string $type
     * @return bool
     */
    public static function isValid(string $type): bool
    {
        return in_array($type, self::values());
    }
}
