<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
