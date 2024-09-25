<?php

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;

it('tracks product stock', function () {
    $product = Product::create(['name' => 'Nintendo switch']);
    $retailer = Retailer::create(['name' => 'Best Buy']);
    expect($product->inStock())->toBeFalse();

    $stock = new Stock([
        'price' => 10000,
        'url' => 'https://foo.com',
        'sku' => '12345',
        'in_stock' => false,
    ]);
    $retailer->addStock($product, $stock);
    expect($stock->fresh()->in_stock)->toBeFalse();

    Http::fake(fn () => ['available' => true, 'price' => 29999]);

    $this->artisan('track');

    $stock->fresh();

    expect($product->inStock())->toBeTrue();
});
