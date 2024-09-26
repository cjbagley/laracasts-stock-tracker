<?php

use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;

it('throws exception when no client found when tracking', function () {
    $this->seed(RetailerWithProductSeeder::class);

    Retailer::first()->update(['name' => 'Foo Retailer']);

    Stock::first()->track();
})->throws(ClientException::class);

it('updates local stock status after tracking', function () {
    $this->seed(RetailerWithProductSeeder::class);

    ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

    /** @var Stock $stock */
    $stock = tap(Stock::first())->track();

    expect($stock->in_stock)->toBeTrue()
        ->and($stock->price)->toBe(9900);
});

class FakeClient implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        return new StockStatus(available: true, price: 9900);
    }
}
