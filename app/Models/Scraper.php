<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scraper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'run',
        'url',
        'period',
        'type_auction_id',
        'last_scraped_at'
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(TypeAuction::class, 'type_auction_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'scraper_id', 'id');
    }
}
