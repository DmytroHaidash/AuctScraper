<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
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

}
