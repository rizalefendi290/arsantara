<?php

namespace App\Filament\Resources\CarouselResource\Pages;

use App\Filament\Resources\CarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarousel extends EditRecord
{
    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->prepareCarouselData($data);
    }

    private function prepareCarouselData(array $data): array
    {
        if (($data['placement'] ?? 'hero') === 'hero') {
            $data['link_url'] = null;
            $data['buttons'] = $this->heroButtons($data['buttons'] ?? []);

            return $data;
        }

        $data['page_key'] = null;
        $data['label'] = null;
        $data['text'] = null;
        $data['label_color'] = null;
        $data['title_color'] = null;
        $data['text_color'] = null;
        $data['buttons'] = null;
        $data['link_url'] = $this->normalizeLink($data['link_url'] ?? null);

        return $data;
    }

    private function heroButtons(?array $buttons): array
    {
        return collect($buttons ?? [])
            ->take(2)
            ->map(function (array $button) {
                $label = trim((string) ($button['label'] ?? ''));
                $url = $this->normalizeLink($button['url'] ?? null);

                if ($label === '' || blank($url)) {
                    return null;
                }

                $variant = ($button['variant'] ?? 'primary') === 'secondary' ? 'secondary' : 'primary';

                return [
                    'label' => $label,
                    'url' => $url,
                    'variant' => $variant,
                    'background_color' => $button['background_color'] ?? ($variant === 'secondary' ? '#ffffff' : '#f3bd12'),
                    'text_color' => $button['text_color'] ?? ($variant === 'secondary' ? '#0f172a' : '#08234c'),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeLink(?string $link): ?string
    {
        $link = trim((string) $link);

        if ($link === '') {
            return null;
        }

        if (str_starts_with($link, 'wa.me/') || str_starts_with($link, 'api.whatsapp.com/')) {
            return 'https://'.$link;
        }

        if (
            str_starts_with($link, 'http://') ||
            str_starts_with($link, 'https://') ||
            str_starts_with($link, '/') ||
            str_starts_with($link, '#')
        ) {
            return $link;
        }

        return '/'.$link;
    }
}
