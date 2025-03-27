<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function getValues(): array
    {
        $cases = self::cases();
        $values = [];
        foreach ($cases as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}
