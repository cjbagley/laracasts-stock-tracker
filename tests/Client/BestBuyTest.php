<?php

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;

it('tracks a product', function () {
    $this->seed(RetailerWithProductSeeder::class);

    $stock = tap(Stock::first())->update(['sku' => '6364253']);

    (new BestBuy)->checkAvailability($stock);
})->throwsNoExceptions();
