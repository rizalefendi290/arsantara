<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcycleDetail extends Model
{
    protected $fillable = [
        'listing_id',
        'brand',
        'model',
        'year',
        'engine',
        'transmission'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
