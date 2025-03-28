<?php

namespace App\Exceptions;

class StockManipulationException extends \Exception
{
    protected ?int $productId;

    public function __construct(
        string $message,
        int $productId = null,
        int $code = 0,
        \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->productId = $productId;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }
}