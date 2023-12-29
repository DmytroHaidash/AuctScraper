<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'old_price',
        'new_price',
        'type_auction_id',
        'scraper_id'
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(TypeAuction::class, 'type_auction_id', 'id');
    }

    public function scraper(): BelongsTo
    {
        return $this->belongsTo(Scraper::class, 'scraper_id', 'id');
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class, 'product_id', 'id');
    }
}
