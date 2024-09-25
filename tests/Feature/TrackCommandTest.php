<?php

use App\Models\Product;
use Database\Seeders\RetailerWithProductSeeder;

it('tracks product stock', function () {
    $this->seed(RetailerWithProductSeeder::class);

    expect(Product::first()->inStock())->toBeFalse();

    Http::fake(fn () => ['available' => true, 'price' => 29999]);

    $this->artisan('track')
        ->expectsOutput('All done!')
        ->assertExitCode(0);

    expect(Product::first()->inStock())->toBeTrue();
});
