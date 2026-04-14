<?php

namespace App\Modules\Job\Enums;

enum Urgency: string
{
    case STANDARD = 'standard';
    case URGENT = 'urgent';

    /**
     * Get all urgency levels
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $urgency) => $urgency->value, self::cases());
    }

    /**
     * Check if urgency is valid
     *
     * @param string $urgency
     * @return bool
     */
    public static function isValid(string $urgency): bool
    {
        return in_array($urgency, self::values());
    }
}
