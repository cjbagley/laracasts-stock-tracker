<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class RetailerWithProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::create(['name' => 'Nintendo switch']);
        $retailer = Retailer::create(['name' => 'Best Buy']);

        $retailer->addStock($product, new Stock([
            'price' => 10000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => false,
        ]));
    }
}
