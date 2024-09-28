<?php

namespace App\Models;

use App\Clients\ClientException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * @throws ClientException
     */
    public function track(): void
    {
        $availability = $this->retailer->client()->checkAvailability($this);

        $this->update([
            'in_stock' => $availability->available,
            'price' => $availability->price,
        ]);

        History::create(['price' => $this->price]);
    }
}
