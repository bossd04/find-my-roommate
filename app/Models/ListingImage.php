<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id',
        'image_path',
        'caption',
        'order',
        'is_primary'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the listing that owns the image.
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
