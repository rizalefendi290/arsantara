<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCommissionRule extends Model
{
    public const CATEGORIES = [
        'rumah' => 'Rumah',
        'tanah' => 'Tanah',
        'otomotif' => 'Otomotif',
        'pinjam_dana_bpkb' => 'Pinjam Dana BPKB',
    ];

    protected $fillable = [
        'category',
        'commission_percent',
        'commission_fixed',
        'is_active',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'commission_fixed' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }
}
