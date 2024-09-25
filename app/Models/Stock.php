<?php

namespace App\Models;

use App\Clients\ClientException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Stock extends Model
{
    protected $table = 'stock';

    use HasFactory;

    protected $fillable = [
        'price',
        'url',
        'sku',
        'in_stock',
    ];

    protected function casts(): array
    {
        return [
            'in_stock' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    /**
     * @throws \Exception
     */
    public function track(): void
    {
        $status = 'App\\Clients\\'.Str::studly($this->retailer->name);

        if (! class_exists($status)) {
            throw new ClientException('Retailer not found for '.$this->retailer->name);
        }

        $available = (new $status)->checkAvailability();

        $this->update([
            'in_stock' => $available->available,
            'price' => $available->price,
        ]);
    }
}
