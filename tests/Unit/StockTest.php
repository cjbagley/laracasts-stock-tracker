<?php

it('throws exception when no client found when tracking', function () {
    $this->seed(\Database\Seeders\RetailerWithProductSeeder::class);

    \App\Models\Retailer::first()->update(['name' => 'Foo Retailer']);

    \App\Models\Stock::first()->track();
})->throws(\App\Clients\ClientException::class);
