<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const HOUSE_SLUG = 'rumah';
    public const LAND_SLUG = 'tanah';
    public const CAR_SLUG = 'mobil';
    public const MOTORCYCLE_SLUG = 'motor';
    public const TRUCK_SLUG = 'truk-kendaraan-komersil';
    public const COMMERCIAL_PROPERTY_SLUGS = ['ruko', 'perkantoran', 'gudang', 'kios'];
    public const PROPERTY_SLUGS = ['rumah', 'tanah', 'ruko', 'perkantoran', 'gudang', 'kios'];
    public const CAR_LIKE_SLUGS = ['mobil', 'truk-kendaraan-komersil'];

    protected $fillable = ['name','slug','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function isHouse(): bool
    {
        return $this->slug === self::HOUSE_SLUG;
    }

    public function isLand(): bool
    {
        return $this->slug === self::LAND_SLUG;
    }

    public function isCommercialProperty(): bool
    {
        return in_array($this->slug, self::COMMERCIAL_PROPERTY_SLUGS, true);
    }

    public function isProperty(): bool
    {
        return in_array($this->slug, self::PROPERTY_SLUGS, true);
    }

    public function isCarLike(): bool
    {
        return in_array($this->slug, self::CAR_LIKE_SLUGS, true);
    }

    public function isMotorcycle(): bool
    {
        return $this->slug === self::MOTORCYCLE_SLUG;
    }

    public function isVehicle(): bool
    {
        return $this->isCarLike() || $this->isMotorcycle();
    }

    public function productCodePrefix(): string
    {
        return match ($this->slug) {
            self::HOUSE_SLUG => 'RMH',
            self::LAND_SLUG => 'TNH',
            self::CAR_SLUG => 'MBL',
            self::MOTORCYCLE_SLUG => 'MTR',
            'ruko' => 'RKO',
            'perkantoran' => 'PKT',
            'gudang' => 'GDG',
            'kios' => 'KOS',
            self::TRUCK_SLUG => 'TRK',
            default => 'PRD',
        };
    }
}
