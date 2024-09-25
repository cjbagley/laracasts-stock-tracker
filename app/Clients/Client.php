<?php

namespace App\Clients;

interface Client
{
    public function checkAvailability(): StockStatus;
}
