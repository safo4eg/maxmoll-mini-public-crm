<?php

namespace App\Enums;

enum StockMoveTypeEnum: string
{
    case INCREMENT = 'increment';
    case DECREMENT = 'decrement';
}
