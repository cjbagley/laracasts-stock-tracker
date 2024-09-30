<?php

use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdateNotification;
use App\UseCases\TrackStock;
use Database\Seeders\RetailerWithProductSeeder;

beforeEach(function () {
    Notification::fake();
    mockClientRequest(price: 1010);
    $this->seed(RetailerWithProductSeeder::class);
    $this->user = User::factory()->create();
    (new TrackStock(Stock::first()))->handle();
});

it('notifies the user', function () {
    Notification::assertSentTimes(ImportantStockUpdateNotification::class, 1);
});

it('refreshes the local stock', function () {
    $stock = Stock::first();

    expect($stock->price)->toBe(1010)
        ->and($stock->in_stock)->toBeTrue();
});

it('records to history', function () {
    expect(History::count())->toBe(1);
});
