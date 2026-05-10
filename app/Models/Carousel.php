<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $fillable = [
        'placement',
        'page_key',
        'image',
        'title',
        'title_color',
        'label',
        'label_color',
        'text',
        'text_color',
        'buttons',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'buttons' => 'array',
    ];

    public function scopeContent($query)
    {
        return $query->where('placement', 'content');
    }

    public function scopeHero($query)
    {
        return $query->where('placement', 'hero');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function heroSlidesFor(?string $pageKey)
    {
        if (blank($pageKey)) {
            return collect();
        }

        return static::hero()
            ->active()
            ->where('page_key', $pageKey)
            ->orderBy('sort_order')
            ->latest()
            ->get()
            ->map(fn ($slide) => [
                'image' => asset('storage/'.$slide->image),
                'label' => $slide->label ?: 'Arsantara',
                'title' => $slide->title ?: 'Arsantara',
                'text' => $slide->text ?: '',
                'label_color' => $slide->label_color ?: '#dbeafe',
                'title_color' => $slide->title_color ?: '#ffffff',
                'text_color' => $slide->text_color ?: '#e2e8f0',
                'buttons' => $slide->buttons ?: [],
            ]);
    }
}
