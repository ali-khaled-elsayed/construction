<?php

namespace App\Modules\Job\Enums;

enum JobStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Get all job statuses
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }

    /**
     * Check if job status is valid
     *
     * @param string $status
     * @return bool
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, self::values());
    }

    /**
     * Get active statuses
     *
     * @return array
     */
    public static function active(): array
    {
        return [
            self::OPEN->value,
            self::IN_PROGRESS->value,
        ];
    }

    /**
     * Get completed statuses
     *
     * @return array
     */
    public static function completed(): array
    {
        return [
            self::COMPLETED->value,
            self::CANCELLED->value,
        ];
    }
}
