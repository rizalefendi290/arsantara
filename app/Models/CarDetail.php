<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDetail extends Model
{
    protected $fillable = [
        'listing_id',
        'brand',
        'model',
        'year',
        'engine',
        'transmission',
        'fuel_type',
        'color',
        'kilometer'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
