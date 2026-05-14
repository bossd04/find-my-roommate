<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'landlord_id',
        'user_id',
        'type',
        'title',
        'description',
        'price',
        'min_price',
        'max_price',
        'location',
        'latitude',
        'longitude',
        'house_rules',
        'image',
        'status',
        'is_active',
        'bedrooms',
        'bathrooms',
        'property_type',
        'area_sqft',
        'furnished',
        'utilities_included',
        'available_from',
        'lease_duration_months',
        'amenities',
        'security_deposit',
        'is_available'
    ];

    /**
     * Scope a query to only include active listings.
     */
    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }

    protected $casts = [
        'price' => 'decimal:2',
        'furnished' => 'boolean',
        'utilities_included' => 'boolean',
        'is_available' => 'boolean',
        'available_from' => 'date',
        'security_deposit' => 'decimal:2',
        'amenities' => 'array',
        'lease_duration_months' => 'integer',
        'area_sqft' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer'
    ];

    protected $dates = [
        'available_from',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the user that owns the listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the landlord that owns the listing.
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function matches()
    {
        return $this->hasMany(\App\Models\RoommateMatch::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get all of the images for the listing.
     */
    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('order');
    }

    /**
     * Get the primary image for the listing.
     */
    public function primaryImage()
    {
        return $this->hasOne(ListingImage::class)->where('is_primary', true);
    }
}
