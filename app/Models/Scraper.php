<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scraper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'run',
        'url',
        'type_auction_id'
    ];

    public function auction(): BelongsTo
    {
        return $this->belongsTo(TypeAuction::class, 'type_auction_id', 'id');
    }
}
