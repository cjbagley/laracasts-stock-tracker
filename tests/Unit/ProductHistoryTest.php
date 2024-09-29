<?php

use App\Clients\ClientException;
use App\Models\History;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;

it('records history when products are tracked', function () {
    $this->seed(RetailerWithProductSeeder::class);

    expect(History::count())->toBe(0);

    Http::fake(fn () => ['salePrice' => 299.99, 'onlineAvailability' => true]);

    try {
        $stock = tap(Stock::first())->track();
    } catch (ClientException $clientException) {
        $this->fail($clientException->getMessage());
    }

    expect(History::count())->toBe(1);

    $history = History::first();

    expect($history->price)->toBe($stock->price)
        ->and($history->in_stock)->toBe($stock->in_stock)
        ->and($history->product_id)->toBe($stock->product_id)
        ->and($history->stock_id)->toBe($stock->id);
});
