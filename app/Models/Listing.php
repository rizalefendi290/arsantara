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
        'is_featured',
        'product_code',
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

    public function scopeInActiveCategory($query)
    {
        return $query->whereHas('category', fn ($category) => $category->where('is_active', true));
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

    public function productCodePrefix(): string
    {
        return match ((int) $this->category_id) {
            1 => 'RMH',
            2 => 'TNH',
            3 => 'MBL',
            4 => 'MTR',
            default => 'PRD',
        };
    }

    public function buildProductCode(): string
    {
        return $this->productCodePrefix().'-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function assignProductCode(bool $force = false): void
    {
        if (!$this->id) {
            return;
        }

        if (!$force && filled($this->product_code)) {
            return;
        }

        $this->forceFill([
            'product_code' => $this->buildProductCode(),
        ])->save();
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
