<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string name
 * @property string link
 * @property string credentials
 * @property mixed created_at
 * @property mixed updated_at
 */
class TypeAuction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'credentials'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'type_auction_id', 'id');
    }

    public function scrapers(): HasMany
    {
        return $this->hasMany(Scraper::class, 'type_auction_id', 'id');
    }
}
