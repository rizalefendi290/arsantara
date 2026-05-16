<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    protected $fillable = [
        'post_id',
        'image'
    ];

    protected static function booted(): void
    {
        static::deleting(function (PostImage $image) {
            if ($image->image) {
                Storage::disk('public')->delete($image->image);
            }
        });
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
