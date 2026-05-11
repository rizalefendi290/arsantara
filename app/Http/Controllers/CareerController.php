<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CareerController extends Controller
{
    public function index()
    {
        $showAll = request()->boolean('show');
        $vacancyQuery = JobVacancy::active()->ordered();
        $totalVacancies = (clone $vacancyQuery)->count();
        $vacancies = $showAll
            ? $vacancyQuery->paginate(9)->withQueryString()
            : $vacancyQuery->take(3)->get();

        return view('careers.index', compact('vacancies', 'showAll', 'totalVacancies'));
    }

    public function show(JobVacancy $jobVacancy)
    {
        abort_unless($jobVacancy->is_active, 404);

        $recommendedVacancies = JobVacancy::active()
            ->whereKeyNot($jobVacancy->id)
            ->ordered()
            ->take(4)
            ->get();

        return view('careers.show', compact('jobVacancy', 'recommendedVacancies'));
    }

    public function apply(JobVacancy $jobVacancy)
    {
        abort_unless($jobVacancy->is_active, 404);

        return view('careers.apply', [
            'jobVacancy' => $jobVacancy,
            'sources' => $this->applicationSources(),
            'educationLevels' => ['SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'],
        ]);
    }

    public function submitApplication(Request $request, JobVacancy $jobVacancy)
    {
        abort_unless($jobVacancy->is_active, 404);

        $request->merge([
            'expected_salary' => preg_replace('/\D+/', '', (string) $request->input('expected_salary')),
        ]);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'date', 'before:today'],
            'source' => ['required', Rule::in($this->applicationSources())],
            'phone' => ['required', 'string', 'max:30'],
            'domicile_address' => ['required', 'string', 'max:1000'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
            'expected_salary' => ['required', 'integer', 'min:0'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'education_level' => ['required', Rule::in(['SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'])],
            'education_institution' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'gpa' => ['required', 'string', 'max:20'],
            'work_experience' => ['required', 'string', 'max:3000'],
            'statement_accepted' => ['accepted'],
            'privacy_accepted' => ['accepted'],
        ], [
            'statement_accepted.accepted' => 'Anda harus menyetujui pernyataan pelamar.',
            'privacy_accepted.accepted' => 'Anda harus menyetujui kebijakan privasi.',
            'cv.required' => 'CV wajib diupload.',
            'cv.mimes' => 'CV harus berupa file PDF, DOC, atau DOCX.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit.',
        ]);

        $cv = $request->file('cv');
        $data['cv_path'] = $cv->store('job-applications/cv');
        $data['cv_original_name'] = $cv->getClientOriginalName();
        $data['statement_accepted'] = $request->boolean('statement_accepted');
        $data['privacy_accepted'] = $request->boolean('privacy_accepted');
        unset($data['cv']);

        $jobVacancy->applications()->create($data);

        return redirect()
            ->route('careers.show', $jobVacancy)
            ->with('success', 'Lamaran Anda berhasil dikirim. Tim kami akan meninjau data yang telah Anda submit.');
    }

    public function wilayahProvinces(): JsonResponse
    {
        return $this->wilayahResponse('provinces', 'https://wilayah.id/api/provinces.json');
    }

    public function wilayahRegencies(string $provinceCode): JsonResponse
    {
        return $this->wilayahResponse("regencies.{$provinceCode}", "https://wilayah.id/api/regencies/{$provinceCode}.json");
    }

    public function wilayahDistricts(string $regencyCode): JsonResponse
    {
        return $this->wilayahResponse("districts.{$regencyCode}", "https://wilayah.id/api/districts/{$regencyCode}.json");
    }

    public function wilayahVillages(string $districtCode): JsonResponse
    {
        return $this->wilayahResponse("villages.{$districtCode}", "https://wilayah.id/api/villages/{$districtCode}.json");
    }

    public function adminIndex()
    {
        $vacancies = JobVacancy::withCount('applications')->ordered()->get();

        return view('admin.careers.index', compact('vacancies'));
    }

    public function adminApplications(Request $request)
    {
        $vacancies = JobVacancy::ordered()->get(['id', 'title']);
        $selectedVacancy = $request->integer('job_vacancy_id');

        $applications = JobApplication::with('jobVacancy')
            ->when($selectedVacancy, fn ($query) => $query->where('job_vacancy_id', $selectedVacancy))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.careers.applications', compact('applications', 'vacancies', 'selectedVacancy'));
    }

    public function showApplication(JobApplication $jobApplication)
    {
        $jobApplication->load('jobVacancy');

        return view('admin.careers.application-show', compact('jobApplication'));
    }

    public function downloadApplicationCv(JobApplication $jobApplication)
    {
        abort_unless(Storage::exists($jobApplication->cv_path), 404);

        return Storage::download($jobApplication->cv_path, $jobApplication->cv_original_name);
    }

    public function acceptApplication(JobApplication $jobApplication)
    {
        $jobApplication->update([
            'status' => 'accepted',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pelamar berhasil diterima.');
    }

    public function rejectApplication(JobApplication $jobApplication)
    {
        $jobApplication->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pelamar berhasil ditolak.');
    }

    public function destroyApplication(JobApplication $jobApplication)
    {
        if ($jobApplication->cv_path) {
            Storage::delete($jobApplication->cv_path);
        }

        $jobApplication->delete();

        return redirect()
            ->route('admin.careers.applications')
            ->with('success', 'Data pelamar berhasil dihapus.');
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

    private function applicationSources(): array
    {
        return [
            'Website Arsantara',
            'Instagram',
            'LinkedIn',
            'Job Portal',
            'Teman/Keluarga',
            'Kampus/Sekolah',
            'Lainnya',
        ];
    }

    private function wilayahResponse(string $cacheKey, string $url): JsonResponse
    {
        try {
            $payload = Cache::remember("wilayah_id.{$cacheKey}", now()->addDays(7), function () use ($url) {
                return Http::timeout(15)
                    ->retry(2, 300)
                    ->acceptJson()
                    ->get($url)
                    ->throw()
                    ->json();
            });

            return response()->json($payload);
        } catch (\Throwable $exception) {
            return response()->json([
                'data' => [],
                'message' => 'Data wilayah belum dapat dimuat. Silakan coba beberapa saat lagi.',
            ], 503);
        }
    }
}
