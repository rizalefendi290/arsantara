<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrganizationMember extends Model
{
    protected $fillable = [
        'photo',
        'name',
        'position',
        'profile',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (OrganizationMember $member) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        });
    }
}
