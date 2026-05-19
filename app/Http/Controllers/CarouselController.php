<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    private array $heroPages = [
        'home' => 'Beranda',
        'properti' => 'Katalog Properti',
        'autoshow' => 'Autoshow',
        'search' => 'Pencarian',
        'rumah.index' => 'Rumah',
        'tanah.index' => 'Tanah',
        'mobil.index' => 'Mobil',
        'motor.index' => 'Motor',
        'about' => 'Tentang Kami',
        'pinjaman.index' => 'Pinjaman Dana',
        'ads.guide' => 'Pasang Iklan',
        'faq' => 'FAQ',
        'terms' => 'Syarat & Ketentuan',
        'privacy' => 'Kebijakan Privasi',
        'testimoni.index' => 'Testimoni',
        'testimoni.create' => 'Buat Testimoni',
        'profile.edit' => 'Profil',
        'careers.index' => 'Karir',
    ];

    public function index()
    {
        $contentCarousels = Carousel::content()->orderBy('sort_order')->latest()->get();
        $heroCarousels = Carousel::hero()->orderBy('page_key')->orderBy('sort_order')->latest()->get();
        $heroPages = $this->heroPages;

        return view('admin.carousel.index', compact('contentCarousels', 'heroCarousels', 'heroPages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placement' => ['required', 'in:content,hero'],
            'page_key' => ['nullable', 'required_if:placement,hero', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:4096'],
            'title' => ['nullable', 'required_if:placement,hero', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'text' => ['nullable', 'string'],
            'label_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'title_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'buttons' => ['nullable', 'array', 'max:2'],
            'buttons.*.label' => ['nullable', 'string', 'max:60'],
            'buttons.*.url' => ['nullable', 'string', 'max:2048'],
            'buttons.*.variant' => ['nullable', 'in:primary,secondary'],
            'buttons.*.background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'buttons.*.text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'link_url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if($request->hasFile('image')){
            $path = $request->file('image')->store($data['placement'] === 'hero' ? 'hero-carousel' : 'carousel','public');

            Carousel::create([
                'placement' => $data['placement'],
                'page_key' => $data['placement'] === 'hero' ? $data['page_key'] : null,
                'image' => $path,
                'title' => $data['title'] ?? null,
                'label' => $data['label'] ?? null,
                'text' => $data['text'] ?? null,
                'label_color' => $data['placement'] === 'hero' ? ($data['label_color'] ?? '#0f172a') : null,
                'title_color' => $data['placement'] === 'hero' ? ($data['title_color'] ?? '#0f172a') : null,
                'text_color' => $data['placement'] === 'hero' ? ($data['text_color'] ?? '#1f2937') : null,
                'buttons' => $data['placement'] === 'hero' ? $this->heroButtons($data['buttons'] ?? []) : null,
                'link_url' => $data['placement'] === 'content' ? $this->normalizeLink($data['link_url'] ?? null) : null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);

        $data = $request->validate([
            'placement' => ['required', 'in:content,hero'],
            'page_key' => ['nullable', 'required_if:placement,hero', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'title' => ['nullable', 'required_if:placement,hero', 'string', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
            'text' => ['nullable', 'string'],
            'label_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'title_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'buttons' => ['nullable', 'array', 'max:2'],
            'buttons.*.label' => ['nullable', 'string', 'max:60'],
            'buttons.*.url' => ['nullable', 'string', 'max:2048'],
            'buttons.*.variant' => ['nullable', 'in:primary,secondary'],
            'buttons.*.background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'buttons.*.text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'link_url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'placement' => $data['placement'],
            'page_key' => $data['placement'] === 'hero' ? $data['page_key'] : null,
            'title' => $data['title'] ?? null,
            'label' => $data['label'] ?? null,
            'text' => $data['text'] ?? null,
            'label_color' => $data['placement'] === 'hero' ? ($data['label_color'] ?? '#0f172a') : null,
            'title_color' => $data['placement'] === 'hero' ? ($data['title_color'] ?? '#0f172a') : null,
            'text_color' => $data['placement'] === 'hero' ? ($data['text_color'] ?? '#1f2937') : null,
            'buttons' => $data['placement'] === 'hero' ? $this->heroButtons($data['buttons'] ?? []) : null,
            'link_url' => $data['placement'] === 'content' ? $this->normalizeLink($data['link_url'] ?? null) : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($carousel->image);
            $data['image'] = $request->file('image')->store($data['placement'] === 'hero' ? 'hero-carousel' : 'carousel', 'public');
        }

        $carousel->update($data);

        return back()->with('success', 'Carousel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);
        Storage::disk('public')->delete($carousel->image);
        $carousel->delete();

        return back();
    }

    private function heroButtons(array $buttons): array
    {
        return collect($buttons)
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
