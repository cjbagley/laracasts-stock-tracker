<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class Walmart implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $r = Http::get('https://foo.bar/test/test/api')->json();

        return new StockStatus($r['available'], $r['price']);
    }
}
