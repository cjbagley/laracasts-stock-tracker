<?php

namespace App\Models;

use App\Clients\Client;
use App\Clients\ClientException;
use Facades\App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function addStock(Product $product, Stock $stock): void
    {
        $stock->product_id = $product->id;
        $this->stock()->save($stock);
    }

    /**
     * @throws ClientException
     */
    public function client(): Client
    {
        return ClientFactory::make($this);
    }
}
