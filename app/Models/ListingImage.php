<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ListingImage extends Model
{
    protected $fillable = ['listing_id','image'];

    protected static function booted(): void
    {
        static::deleting(function (ListingImage $image) {
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }
        });
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
