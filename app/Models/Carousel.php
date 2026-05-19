<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted(): void
    {
        static::deleting(function (Carousel $carousel) {
            if ($carousel->image) {
                Storage::disk('public')->delete($carousel->image);
            }
        });
    }

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
                'label_color' => $slide->label_color ?: '#0f172a',
                'title_color' => $slide->title_color ?: '#0f172a',
                'text_color' => $slide->text_color ?: '#1f2937',
                'buttons' => collect($slide->buttons ?: [])
                    ->map(function (array $button) {
                        $variant = ($button['variant'] ?? 'primary') === 'secondary' ? 'secondary' : 'primary';

                        return [
                            'label' => $button['label'] ?? '',
                            'url' => $button['url'] ?? '',
                            'variant' => $variant,
                            'background_color' => $button['background_color'] ?? ($variant === 'secondary' ? '#ffffff' : '#f3bd12'),
                            'text_color' => $button['text_color'] ?? ($variant === 'secondary' ? '#0f172a' : '#08234c'),
                        ];
                    })
                    ->all(),
            ]);
    }
}
