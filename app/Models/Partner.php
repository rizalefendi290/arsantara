<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Partner extends Model
{
    protected $fillable = [
        'logo',
        'name',
        'category',
        'website_url',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Partner $partner) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
        });
    }
}
