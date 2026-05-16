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
        'seller_price',
        'offer_price',
        'sold_price',
        'minimum_dp',
        'minimum_nego_price',
        'showroom_dp_in',
        'installment',
        'tenor',
        'commission',
        'location',
        'contact_name',
        'contact_phone',
        'ownership_name',
        'document_status',
        'annual_tax_status',
        'five_year_tax_status',
        'ownership_status',
        'plate_number',
        'condition',
        'status',
        'is_featured',
        'product_code',
        'notes',
        'progress_status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'integer',
        'discount_price' => 'integer',
        'seller_price' => 'integer',
        'offer_price' => 'integer',
        'sold_price' => 'integer',
        'minimum_dp' => 'integer',
        'minimum_nego_price' => 'integer',
        'showroom_dp_in' => 'integer',
        'installment' => 'integer',
        'commission' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Listing $listing) {
            $listing->images()->get()->each->delete();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInActiveCategory($query)
    {
        return $query->whereHas('category', fn ($category) => $category->where('is_active', true));
    }

    public function scopeInCategorySlug($query, string $slug)
    {
        return $query->whereHas('category', fn ($category) => $category->where('slug', $slug));
    }

    public function scopeInCategorySlugs($query, array $slugs)
    {
        return $query->whereHas('category', fn ($category) => $category->whereIn('slug', $slugs));
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
        return $this->category?->productCodePrefix() ?? 'PRD';
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
