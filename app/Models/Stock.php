<?php

namespace App\Models;

use App\Clients\ClientException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function history(): HasMany
    {
        return $this->hasMany(History::class);
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

        $this->recordHistory();
    }

    public function recordHistory(): void
    {
        $this->history()->create([
            'product_id' => $this->product_id,
            'price' => $this->price,
            'in_stock' => $this->in_stock,
        ]);
    }
}
