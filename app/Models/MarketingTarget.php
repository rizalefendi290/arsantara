<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingTarget extends Model
{
    public const TYPES = [
        'monthly' => 'Bulanan',
        'yearly' => 'Tahunan',
    ];

    protected $fillable = [
        'user_id',
        'target_type',
        'period_year',
        'period_month',
        'target_amount',
        'notes',
    ];

    protected $casts = [
        'period_year' => 'integer',
        'period_month' => 'integer',
        'target_amount' => 'integer',
    ];

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function targetLabel(): string
    {
        return self::TYPES[$this->target_type] ?? ucfirst($this->target_type);
    }
}
