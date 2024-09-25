<?php

namespace App\Models;

use Http;
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

    public function track(): void
    {
        if ($this->retailer->name == 'Best Buy') {
            $r = Http::get('https://foo.bar/test/test/api')->json();
            $this->update([
                'in_stock' => $r['available'],
                'price' => $r['price'],
            ]);
        }
    }
}
