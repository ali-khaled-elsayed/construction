<?php

namespace App\Modules\Job\Enums;

enum JobType: string
{
    case FULL = 'full';
    case PARTIAL = 'partial';

    /**
     * Get all job types
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }

    /**
     * Check if job type is valid
     *
     * @param string $type
     * @return bool
     */
    public static function isValid(string $type): bool
    {
        return in_array($type, self::values());
    }
}
