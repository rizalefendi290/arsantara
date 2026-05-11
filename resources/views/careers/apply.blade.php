@extends('layouts.app')

@section('content')
@php
    $fieldClass = 'mt-2 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-blue-600 focus:ring-blue-600';
    $labelClass = 'text-sm font-bold text-slate-800';
@endphp

<main class="bg-slate-50 px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <a href="{{ route('careers.show', $jobVacancy) }}" class="mb-6 inline-flex text-sm font-bold text-blue-700 hover:text-blue-800">
            &larr; Kembali ke detail lowongan
        </a>

        <div class="mb-8 rounded-2xl bg-[#09285d] px-6 py-8 text-white shadow-lg sm:px-8">
            <p class="text-sm font-bold uppercase tracking-wide text-blue-100">Form Lamaran Pekerjaan</p>
            <h1 class="mt-2 text-3xl font-black sm:text-4xl">{{ $jobVacancy->title }}</h1>
            <p class="mt-3 text-sm font-semibold text-blue-100">{{ $jobVacancy->location ?: 'Fleksibel' }} - {{ $jobVacancy->employment_type ?: 'Staff' }}</p>
        </div>

        <form method="POST" action="{{ route('careers.apply.submit', $jobVacancy) }}" enctype="multipart/form-data"
            x-data="applicationForm({
                expectedSalary: @js(old('expected_salary', '')),
                provinceName: @js(old('province', '')),
                cityName: @js(old('city', '')),
                districtName: @js(old('district', '')),
                villageName: @js(old('village', '')),
            })"
            class="space-y-8">
            @csrf

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-black text-slate-950">Data Diri</h2>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="full_name" class="{{ $labelClass }}">Nama Lengkap sesuai KTP</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="{{ $labelClass }}">Alamat Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="nik" class="{{ $labelClass }}">NIK</label>
                        <input id="nik" name="nik" type="text" inputmode="numeric" maxlength="16" value="{{ old('nik') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                    </div>

                    <div>
                        <label for="gender" class="{{ $labelClass }}">Jenis Kelamin</label>
                        <select id="gender" name="gender" required class="{{ $fieldClass }}">
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" @selected(old('gender') === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('gender') === 'Perempuan')>Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div>
                        <label for="birth_date" class="{{ $labelClass }}">Tanggal Lahir</label>
                        <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                    </div>

                    <div>
                        <label for="source" class="{{ $labelClass }}">Sumber Informasi Lowongan</label>
                        <select id="source" name="source" required class="{{ $fieldClass }}">
                            <option value="">Pilih sumber informasi</option>
                            @foreach($sources as $source)
                                <option value="{{ $source }}" @selected(old('source') === $source)>{{ $source }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <label for="phone" class="{{ $labelClass }}">Nomor Telepon/WA</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <label for="expected_salary" class="{{ $labelClass }}">Ekspektasi Gaji</label>
                        <input id="expected_salary" name="expected_salary" type="text" inputmode="numeric" x-model="salaryDisplay" @input="formatSalary" required class="{{ $fieldClass }}" placeholder="Contoh: Rp 5.000.000">
                        <x-input-error :messages="$errors->get('expected_salary')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-5">
                    <label for="domicile_address" class="{{ $labelClass }}">Alamat Domisili</label>
                    <textarea id="domicile_address" name="domicile_address" rows="4" required class="{{ $fieldClass }}">{{ old('domicile_address') }}</textarea>
                    <x-input-error :messages="$errors->get('domicile_address')" class="mt-2" />
                </div>

                <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="province" class="{{ $labelClass }}">Provinsi</label>
                        <input type="hidden" name="province" :value="selectedName(provinces, provinceCode)">
                        <select id="province" x-model="provinceCode" @change="onProvinceChange" required class="{{ $fieldClass }}" :disabled="loading.provinces">
                            <option value="" x-text="loading.provinces ? 'Memuat provinsi...' : 'Pilih provinsi'"></option>
                            <template x-for="item in provinces" :key="item.code">
                                <option :value="item.code" x-text="item.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('province')" class="mt-2" />
                    </div>

                    <div>
                        <label for="city" class="{{ $labelClass }}">Kabupaten/Kota</label>
                        <input type="hidden" name="city" :value="selectedName(regencies, regencyCode)">
                        <select id="city" x-model="regencyCode" @change="onRegencyChange" required class="{{ $fieldClass }}" :disabled="!provinceCode || loading.regencies">
                            <option value="" x-text="loading.regencies ? 'Memuat kabupaten/kota...' : 'Pilih kabupaten/kota'"></option>
                            <template x-for="item in regencies" :key="item.code">
                                <option :value="item.code" x-text="item.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
                    </div>

                    <div>
                        <label for="district" class="{{ $labelClass }}">Kecamatan</label>
                        <input type="hidden" name="district" :value="selectedName(districts, districtCode)">
                        <select id="district" x-model="districtCode" @change="onDistrictChange" required class="{{ $fieldClass }}" :disabled="!regencyCode || loading.districts">
                            <option value="" x-text="loading.districts ? 'Memuat kecamatan...' : 'Pilih kecamatan'"></option>
                            <template x-for="item in districts" :key="item.code">
                                <option :value="item.code" x-text="item.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('district')" class="mt-2" />
                    </div>

                    <div>
                        <label for="village" class="{{ $labelClass }}">Kelurahan</label>
                        <input type="hidden" name="village" :value="selectedName(villages, villageCode)">
                        <select id="village" x-model="villageCode" required class="{{ $fieldClass }}" :disabled="!districtCode || loading.villages">
                            <option value="" x-text="loading.villages ? 'Memuat kelurahan/desa...' : 'Pilih kelurahan/desa'"></option>
                            <template x-for="item in villages" :key="item.code">
                                <option :value="item.code" x-text="item.name"></option>
                            </template>
                        </select>
                        <x-input-error :messages="$errors->get('village')" class="mt-2" />
                    </div>
                </div>
                <p x-show="locationError" x-text="locationError" class="mt-3 text-sm font-semibold text-red-600"></p>

                <div class="mt-5">
                    <label for="cv" class="{{ $labelClass }}">Upload CV</label>
                    <input id="cv" name="cv" type="file" accept=".pdf,.doc,.docx" required class="{{ $fieldClass }} file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-bold file:text-blue-700">
                    <p class="mt-2 text-xs font-medium text-slate-500">Format PDF, DOC, atau DOCX. Maksimal 5 MB.</p>
                    <x-input-error :messages="$errors->get('cv')" class="mt-2" />
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-black text-slate-950">Riwayat Pendidikan</h2>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="education_level" class="{{ $labelClass }}">Jenjang Pendidikan</label>
                        <select id="education_level" name="education_level" required class="{{ $fieldClass }}">
                            <option value="">Pilih jenjang pendidikan</option>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level }}" @selected(old('education_level') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('education_level')" class="mt-2" />
                    </div>

                    <div>
                        <label for="education_institution" class="{{ $labelClass }}">Nama Instansi Pendidikan</label>
                        <input id="education_institution" name="education_institution" type="text" value="{{ old('education_institution') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('education_institution')" class="mt-2" />
                    </div>

                    <div>
                        <label for="major" class="{{ $labelClass }}">Jurusan</label>
                        <input id="major" name="major" type="text" value="{{ old('major') }}" required class="{{ $fieldClass }}">
                        <x-input-error :messages="$errors->get('major')" class="mt-2" />
                    </div>

                    <div>
                        <label for="gpa" class="{{ $labelClass }}">GPA / IPK</label>
                        <input id="gpa" name="gpa" type="text" value="{{ old('gpa') }}" required class="{{ $fieldClass }}" placeholder="Contoh: 3.50">
                        <x-input-error :messages="$errors->get('gpa')" class="mt-2" />
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-black text-slate-950">Pengalaman Kerja</h2>
                <textarea id="work_experience" name="work_experience" rows="6" required class="{{ $fieldClass }}" placeholder="Tuliskan pengalaman kerja, nama perusahaan, posisi, durasi, dan tanggung jawab utama.">{{ old('work_experience') }}</textarea>
                <x-input-error :messages="$errors->get('work_experience')" class="mt-2" />
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" x-data="{ statementModal: false, privacyModal: false }">
                <div class="space-y-4">
                    <label class="flex gap-3 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="statement_accepted" value="1" required @checked(old('statement_accepted')) class="mt-1 rounded border-slate-300 text-blue-700 focus:ring-blue-600">
                        <span>
                            Saya setuju dengan
                            <button type="button" class="font-black text-blue-700 underline underline-offset-4" @click="statementModal = true">pernyataan berikut</button>.
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('statement_accepted')" />

                    <label class="flex gap-3 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="privacy_accepted" value="1" required @checked(old('privacy_accepted')) class="mt-1 rounded border-slate-300 text-blue-700 focus:ring-blue-600">
                        <span>
                            Saya setuju dengan
                            <button type="button" class="font-black text-blue-700 underline underline-offset-4" @click="privacyModal = true">kebijakan privasi yang berlaku</button>.
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('privacy_accepted')" />
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('careers.show', $jobVacancy) }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 px-6 font-bold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded-xl bg-red-700 px-8 font-black text-white transition hover:bg-red-800">
                        Kirim Lamaran
                    </button>
                </div>

                <div x-cloak x-show="statementModal" class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4" @keydown.escape.window="statementModal = false">
                    <div class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl" @click.outside="statementModal = false">
                        <h3 class="text-2xl font-black text-slate-950">Pernyataan Pelamar</h3>
                        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
                            <p>Saya menyatakan bahwa seluruh data, dokumen, dan informasi yang saya berikan dalam formulir lamaran ini adalah benar, akurat, dan dapat dipertanggungjawabkan.</p>
                            <p>Saya bersedia mengikuti proses seleksi sesuai ketentuan perusahaan dan memahami bahwa data yang tidak benar dapat membatalkan proses lamaran maupun hubungan kerja di kemudian hari.</p>
                            <p>Saya memberikan izin kepada perusahaan untuk melakukan verifikasi terhadap informasi pendidikan, pengalaman kerja, dan data pendukung lain yang relevan dengan proses rekrutmen.</p>
                        </div>
                        <div class="mt-6 text-right">
                            <button type="button" class="rounded-xl bg-blue-700 px-5 py-3 font-bold text-white" @click="statementModal = false">Saya Mengerti</button>
                        </div>
                    </div>
                </div>

                <div x-cloak x-show="privacyModal" class="fixed inset-0 z-[90] flex items-center justify-center bg-slate-950/60 px-4" @keydown.escape.window="privacyModal = false">
                    <div class="max-h-[85vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl" @click.outside="privacyModal = false">
                        <h3 class="text-2xl font-black text-slate-950">Kebijakan Privasi Rekrutmen</h3>
                        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-700">
                            <p>Data pribadi pelamar digunakan untuk kebutuhan administrasi, penilaian, komunikasi, dan proses seleksi kerja pada posisi yang dilamar.</p>
                            <p>Perusahaan menjaga kerahasiaan data pelamar dan hanya membagikannya kepada pihak internal atau pihak terkait yang berwenang untuk mendukung proses rekrutmen.</p>
                            <p>Dokumen lamaran dapat disimpan selama periode yang diperlukan untuk proses rekrutmen dan kebutuhan dokumentasi perusahaan sesuai kebijakan yang berlaku.</p>
                        </div>
                        <div class="mt-6 text-right">
                            <button type="button" class="rounded-xl bg-blue-700 px-5 py-3 font-bold text-white" @click="privacyModal = false">Saya Mengerti</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function applicationForm(initial) {
        return {
            wilayahBaseUrl: @js(url('/api/wilayah')),
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
            provinceCode: '',
            regencyCode: '',
            districtCode: '',
            villageCode: '',
            salaryDisplay: '',
            locationError: '',
            loading: {
                provinces: false,
                regencies: false,
                districts: false,
                villages: false,
            },
            async init() {
                this.salaryDisplay = this.toRupiah(initial.expectedSalary);
                await this.loadProvinces();
                await this.restoreOldLocation();
            },
            async fetchArea(path, loadingKey) {
                this.loading[loadingKey] = true;
                this.locationError = '';

                try {
                    const response = await fetch(`${this.wilayahBaseUrl}/${path}`, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Data wilayah tidak dapat dimuat.');
                    }

                    const payload = await response.json();
                    return payload.data || [];
                } catch (error) {
                    this.locationError = 'Data wilayah gagal dimuat. Silakan coba pilih ulang beberapa saat lagi.';
                    return [];
                } finally {
                    this.loading[loadingKey] = false;
                }
            },
            async loadProvinces() {
                this.provinces = await this.fetchArea('provinces', 'provinces');
            },
            async loadRegencies() {
                this.regencies = this.provinceCode ? await this.fetchArea(`regencies/${this.provinceCode}`, 'regencies') : [];
            },
            async loadDistricts() {
                this.districts = this.regencyCode ? await this.fetchArea(`districts/${this.regencyCode}`, 'districts') : [];
            },
            async loadVillages() {
                this.villages = this.districtCode ? await this.fetchArea(`villages/${this.districtCode}`, 'villages') : [];
            },
            async restoreOldLocation() {
                this.provinceCode = this.findCodeByName(this.provinces, initial.provinceName);

                if (!this.provinceCode) {
                    return;
                }

                await this.loadRegencies();
                this.regencyCode = this.findCodeByName(this.regencies, initial.cityName);

                if (!this.regencyCode) {
                    return;
                }

                await this.loadDistricts();
                this.districtCode = this.findCodeByName(this.districts, initial.districtName);

                if (!this.districtCode) {
                    return;
                }

                await this.loadVillages();
                this.villageCode = this.findCodeByName(this.villages, initial.villageName);
            },
            async onProvinceChange() {
                this.regencyCode = '';
                this.districtCode = '';
                this.villageCode = '';
                this.regencies = [];
                this.districts = [];
                this.villages = [];
                await this.loadRegencies();
            },
            async onRegencyChange() {
                this.districtCode = '';
                this.villageCode = '';
                this.districts = [];
                this.villages = [];
                await this.loadDistricts();
            },
            async onDistrictChange() {
                this.villageCode = '';
                this.villages = [];
                await this.loadVillages();
            },
            selectedName(items, code) {
                return (items.find((item) => item.code === code) || {}).name || '';
            },
            findCodeByName(items, name) {
                const normalizedName = String(name || '').toLowerCase();

                if (!normalizedName) {
                    return '';
                }

                return (items.find((item) => item.name.toLowerCase() === normalizedName) || {}).code || '';
            },
            formatSalary() {
                this.salaryDisplay = this.toRupiah(this.salaryDisplay);
            },
            toRupiah(value) {
                const numericValue = String(value || '').replace(/\D/g, '');

                if (!numericValue) {
                    return '';
                }

                return 'Rp ' + numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            },
        };
    }
</script>
@endpush
