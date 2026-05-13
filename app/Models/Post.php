<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
    'title',
    'content',
    'source_name',
    'source_url'
    ];

    protected static function booted(): void
    {
        static::deleting(function (Post $post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->images()->get()->each->delete();
        });
    }

        public function images()
    {
        return $this->hasMany(PostImage::class);
    }
}
