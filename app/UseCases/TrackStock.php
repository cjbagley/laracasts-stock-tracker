<?php

namespace App\UseCases;

use App\Clients\ClientException;
use App\Clients\StockStatus;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdateNotification;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackStock
{
    use Dispatchable;

    private StockStatus $status;

    public function __construct(private readonly Stock $stock) {}

    public function handle(): void
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordHistory();
    }

    /**
     * @throws ClientException
     */
    private function checkAvailability(): void
    {
        $this->status = $this->stock
            ->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    private function notifyUser(): void
    {
        if (! $this->status->available) {
            return;
        }

        if ($this->stock->in_stock) {
            return;
        }

        User::first()?->notify(new ImportantStockUpdateNotification($this->stock));
    }

    private function refreshStock(): void
    {
        $this->stock->update([
            'in_stock' => $this->status->available,
            'price' => $this->status->price,
        ]);
    }

    private function recordHistory(): void
    {
        History::create([
            'stock_id' => $this->stock->id,
            'product_id' => $this->stock->product->id,
            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
        ]);
    }
}
