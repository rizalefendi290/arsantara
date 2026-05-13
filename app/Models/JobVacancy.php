<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    protected $fillable = [
        'title',
        'employment_type',
        'location',
        'deadline',
        'description',
        'requirements',
        'apply_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (JobVacancy $vacancy) {
            $vacancy->applications()->get()->each->delete();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->latest();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
