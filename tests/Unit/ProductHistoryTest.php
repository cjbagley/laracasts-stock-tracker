<?php

use App\Clients\ClientException;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RetailerWithProductSeeder;

beforeEach(function () {
    Notification::fake();
    $this->seed(RetailerWithProductSeeder::class);
    $this->user = User::factory()->create(['email' => 'colin@test.com']);
});

it('records history when products are tracked', function () {
    $stock_price = 299;

    mockClientRequest(price: $stock_price);

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
