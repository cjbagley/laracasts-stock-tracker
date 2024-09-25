<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public function checkAvailability(): StockStatus
    {
        $r = Http::get('https://foo.bar/test/test/api')->json();

        return new StockStatus($r['available'], $r['price']);
    }
}
