<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'location',
        'condition',
        'status',
        'is_featured'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class);
    }

    public function propertyDetail()
    {
        return $this->hasOne(PropertyDetail::class);
    }

    public function carDetail()
    {
        return $this->hasOne(CarDetail::class);
    }

    public function motorcycleDetail()
    {
        return $this->hasOne(MotorcycleDetail::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function property()
    {
        return $this->hasOne(PropertyDetail::class);
    }

    public function car()
    {
        return $this->hasOne(CarDetail::class);
    }

    public function motorcycle()
    {
        return $this->hasOne(MotorcycleDetail::class);
    }

}
