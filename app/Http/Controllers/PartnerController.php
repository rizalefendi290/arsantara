<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('sort_order')
            ->latest()
            ->get();

        return view('admin.partners.index', compact('partners'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $data['website_url'] = $this->normalizeUrl($data['website_url'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        Partner::create($data);

        return back()->with('success', 'Data mitra berhasil ditambahkan');
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $this->validatedData($request, false);

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }

            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $data['website_url'] = $this->normalizeUrl($data['website_url'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $partner->update($data);

        return back()->with('success', 'Data mitra berhasil diperbarui');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return back()->with('success', 'Data mitra berhasil dihapus');
    }

    private function validatedData(Request $request, bool $logoRequired = true): array
    {
        return $request->validate([
            'logo' => [$logoRequired ? 'required' : 'nullable', 'image', 'max:4096'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return 'https://'.$url;
    }
}
