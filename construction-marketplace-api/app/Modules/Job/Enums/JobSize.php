<?php

namespace App\Modules\Job\Enums;


enum JobSize: string
{
    case SMALL = 'small';
    case MEDIUM = 'medium';
    case LARGE = 'large';

    /**
     * Get all job sizes
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $size) => $size->value, self::cases());
    }

    /**
     * Check if job size is valid
     *
     * @param string $size
     * @return bool
     */
    public static function isValid(string $size): bool
    {
        return in_array($size, self::values());
    }
}
