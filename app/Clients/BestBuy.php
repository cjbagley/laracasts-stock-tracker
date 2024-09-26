<?php

namespace App\Clients;

use App\Models\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    private const BASE_URL = 'https://api.bestbuy.com/v1';

    public function checkAvailability(Stock $stock): StockStatus
    {
        $r = Http::get($this->productEndpoint($stock->sku))->json();

        return new StockStatus($r['onlineAvailability'], $this->convertDollarsToCents($r['salePrice']));
    }

    public function productEndpoint(string $sku): string
    {
        return sprintf('%s/products/%s.json?apiKey=%s',
            self::BASE_URL, $sku, config('services.clients.bestbuy.api')
        );
    }

    private function convertDollarsToCents(float $amount): int
    {
        return (int) ($amount * 100);
    }
}
