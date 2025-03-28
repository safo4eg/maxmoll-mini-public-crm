<?php

namespace App\Enums;

enum StockErrorCodeEnum: int
{
    case INSUFFICIENT_STOCK = 1001; // Недостаточно товара
    case PRODUCT_NOT_FOUND_ON_WAREHOUSE = 1002; // товар не связан со складом
}
