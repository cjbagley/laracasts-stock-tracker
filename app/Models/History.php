<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class History extends Model
{
    protected $table = 'product_history';

    protected function casts(): array
    {
        return [
            'in_stock' => 'boolean',
        ];
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    protected $fillable = [
        'price',
        'in_stock',
        'product_id',
        'stock_id',
    ];
}
