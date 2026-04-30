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
        'discount_price',
        'location',
        'condition',
        'status',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'integer',
        'discount_price' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function hasDiscount(): bool
    {
        return filled($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    public function finalPrice(): int
    {
        return $this->hasDiscount() ? $this->discount_price : $this->price;
    }

    public function discountPercent(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }

        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

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

    public function views()
    {
        return $this->hasMany(ListingView::class);
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
