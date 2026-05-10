<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $vacancies = JobVacancy::active()
            ->ordered()
            ->paginate(9);

        return view('careers.index', compact('vacancies'));
    }

    public function show(JobVacancy $jobVacancy)
    {
        abort_unless($jobVacancy->is_active, 404);

        return view('careers.show', compact('jobVacancy'));
    }

    public function adminIndex()
    {
        $vacancies = JobVacancy::ordered()->get();

        return view('admin.careers.index', compact('vacancies'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['apply_url'] = $this->normalizeUrl($data['apply_url'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        JobVacancy::create($data);

        return back()->with('success', 'Lowongan pekerjaan berhasil ditambahkan');
    }

    public function update(Request $request, JobVacancy $jobVacancy)
    {
        $data = $this->validatedData($request);
        $data['apply_url'] = $this->normalizeUrl($data['apply_url'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $jobVacancy->update($data);

        return back()->with('success', 'Lowongan pekerjaan berhasil diperbarui');
    }

    public function destroy(JobVacancy $jobVacancy)
    {
        $jobVacancy->delete();

        return back()->with('success', 'Lowongan pekerjaan berhasil dihapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'apply_url' => ['nullable', 'string', 'max:2048'],
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

        if (
            str_starts_with($url, 'http://') ||
            str_starts_with($url, 'https://') ||
            str_starts_with($url, '/') ||
            str_starts_with($url, '#')
        ) {
            return $url;
        }

        if (str_starts_with($url, 'wa.me/') || str_starts_with($url, 'api.whatsapp.com/')) {
            return 'https://'.$url;
        }

        return 'https://'.$url;
    }
}
