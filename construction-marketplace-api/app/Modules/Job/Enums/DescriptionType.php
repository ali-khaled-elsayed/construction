<?php

namespace App\Modules\Job\Enums;

enum DescriptionType: string
{
    case BASIC = 'basic';
    case DETAILED = 'detailed';

    /**
     * Get all description types
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }

    /**
     * Check if description type is valid
     *
     * @param string $type
     * @return bool
     */
    public static function isValid(string $type): bool
    {
        return in_array($type, self::values());
    }
}
