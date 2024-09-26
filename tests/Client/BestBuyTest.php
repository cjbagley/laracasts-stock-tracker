<?php

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;

it('tracks a product', function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update(['sku' => '6364253']);

    (new BestBuy)->checkAvailability($stock);
})->throwsNoExceptions()->group('api');

it('creates correct response', function () {
    Http::fake(fn () => ['salePrice' => 299.99, 'onlineAvailability' => true]);

    $stock = new Stock;
    $stock->sku = '12345';

    $stock_status = (new BestBuy)->checkAvailability($stock);

    expect($stock_status->price)->toBe(29999)
        ->and($stock_status->available)->toBeTrue();
});
