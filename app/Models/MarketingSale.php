<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingSale extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'cancel' => 'Cancel',
    ];

    protected $fillable = [
        'user_id',
        'category',
        'product_name',
        'deal_price',
        'customer_name',
        'deal_date',
        'notes',
        'status',
        'commission_percent',
        'commission_fixed',
        'commission_total',
        'approved_by',
        'approved_at',
        'rejection_note',
    ];

    protected $casts = [
        'deal_price' => 'integer',
        'deal_date' => 'date',
        'commission_percent' => 'decimal:2',
        'commission_fixed' => 'integer',
        'commission_total' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeInDealPeriod($query, ?string $month, ?int $year = null)
    {
        return $query
            ->when($month, fn ($q) => $q->whereYear('deal_date', substr($month, 0, 4))->whereMonth('deal_date', substr($month, 5, 2)))
            ->when(! $month && $year, fn ($q) => $q->whereYear('deal_date', $year));
    }

    public function categoryLabel(): string
    {
        return SalesCommissionRule::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }
}
