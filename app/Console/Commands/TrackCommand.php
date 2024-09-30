<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Product::all()
            ->tap(fn ($products) => $this->output->progressStart($products->count()))
            ->each(function (Product $product) {
                $product->track();
                $this->output->progressAdvance();
            });

        $this->showResults();
    }

    public function showResults(): void
    {
        $this->output->progressFinish();

        $results = Product::leftJoin('stock', 'products.id', '=', 'stock.product_id')
            ->get(['name', 'price', 'url', 'in_stock']);

        $this->table(['Name', 'Price', 'url', 'In stock'], $results);
    }
}
