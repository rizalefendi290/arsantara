<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDetail extends Model
{
    protected $fillable = [
        'listing_id',
        'house_type',
        'land_area',
        'building_area',
        'bedrooms',
        'bathrooms',
        'floors',
        'certificate',
        'facilities'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
