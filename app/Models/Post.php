<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
    'title',
    'content',
    'source_name',
    'source_url'
    ];

        public function images()
    {
        return $this->hasMany(PostImage::class);
    }
}
