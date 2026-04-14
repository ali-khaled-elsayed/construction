<?php

namespace App\Enums;

enum ShortListingStatus: string
{
    case INTERESTED = 'interested';
    case SHORTLISTED = 'shortlisted';
    case PAID = 'paid';
    case WITHDRAW = 'withdraw';
    case CANCELLED = 'cancelled';
    case ACCEPTED = 'accepted';

    /**
     * Get all short listing statuses
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }

    /**
     * Check if status is valid
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
            self::INTERESTED->value,
            self::SHORTLISTED->value,
            self::PAID->value,
            self::ACCEPTED->value,
        ];
    }

    /**
     * Get completed/inactive statuses
     *
     * @return array
     */
    public static function inactive(): array
    {
        return [
            self::WITHDRAW->value,
            self::CANCELLED->value,
        ];
    }
}
