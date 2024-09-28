<?php

use App\Models\History;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;

it('records history when products are tracked', function () {
    $this->seed(RetailerWithProductSeeder::class);

    expect(History::count())->toBe(0);

    Http::fake(fn () => ['salePrice' => 299.99, 'onlineAvailability' => true]);

    $stock = tap(Stock::first())->track();

    expect(History::count())->toBe(1);

    $history = History::first();

    expect($history->price)->toBe($stock->price);
});
