<?php

namespace App\Enums;

enum StockErrorCodeEnum: int
{
    case INSUFFICIENT_STOCK = 1001; // Недостаточно товара
}
