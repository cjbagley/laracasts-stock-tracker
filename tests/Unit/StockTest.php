<?php

use App\Clients\ClientException;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;

beforeEach(function () {
    $this->seed(RetailerWithProductSeeder::class);
});

it('throws exception when no client found when tracking', function () {
    Retailer::first()->update(['name' => 'Unknown Retailer']);

    Stock::first()->track();
})->throws(ClientException::class);

it('updates local stock status after tracking', function () {
    mockClientRequest(price: 9900);

    /** @var Stock $stock */
    $stock = tap(Stock::first())->track();

    expect($stock->in_stock)->toBeTrue()
        ->and($stock->price)->toBe(9900);
});
