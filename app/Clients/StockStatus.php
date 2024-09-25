<?php

namespace App\Clients;

final readonly class StockStatus
{
    public function __construct(public bool $available, public int $price) {}
}
