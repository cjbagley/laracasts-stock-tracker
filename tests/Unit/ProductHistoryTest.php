<?php

use App\Clients\ClientException;
use App\Clients\StockStatus;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RetailerWithProductSeeder;
use Facades\App\Clients\ClientFactory;

it('records history when products are tracked', function () {
    $this->seed(RetailerWithProductSeeder::class);
    $stock_price = 299;

    ClientFactory::shouldReceive('make->checkAvailability')
        ->andReturn(new StockStatus(available: true, price: $stock_price));

    $product = Product::first();

    expect($product->history->count())->toBe(0);

    try {
        $product->track();
    } catch (ClientException $clientException) {
        $this->fail($clientException->getMessage());
    }

    expect($product->refresh()->history->count())->toBe(1);

    $history = $product->history->first();

    expect($history->price)->toBe($stock_price)
        ->and($history->in_stock)->toBeTrue()
        ->and($history->product_id)->toBe($product->id)
        ->and($history->stock_id)->toBe($product->stock[0]->id);
});

it('notifies the user product comes into stock', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'colin@test.com']);

    $this->seed(RetailerWithProductSeeder::class);
    ClientFactory::shouldReceive('make->checkAvailability')
        ->andReturn(new StockStatus(available: true, price: 299));

    $this->artisan('track');

    Notification::assertSentTo($user, \App\Notifications\ImportantStockUpdateNotification::class);
});
