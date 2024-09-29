<?php

namespace App\Listeners;

use App\Events\NowInStock;
use App\Models\User;
use App\Notifications\ImportantStockUpdateNotification;

class SendStockUpdateNotification
{
    /**
     * Handle the event.
     */
    public function handle(NowInStock $event): void
    {
        User::first()?->notify(new ImportantStockUpdateNotification($event->stock));
    }
}
