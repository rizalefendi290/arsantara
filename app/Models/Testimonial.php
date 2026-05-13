<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    protected $fillable = [
    'name',
    'job',
    'message',
    'photo',
    'rating',
    'is_active'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Testimonial $testimonial) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
        });
    }
}
