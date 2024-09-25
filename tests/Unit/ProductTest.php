<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;

test('check stock for products at retailer', function () {
    $product = Product::create(['name' => 'Nintendo switch']);
    $retailer = Retailer::create(['name' => 'Best Buy']);
    expect($product->inStock())->toBeFalse();

    $stock = new Stock([
        'price' => 10000,
        'url' => 'https://foo.com',
        'sku' => '12345',
        'in_stock' => true,
    ]);
    $retailer->addStock($product, $stock);

    expect($product->inStock())->toBeTrue();
});
