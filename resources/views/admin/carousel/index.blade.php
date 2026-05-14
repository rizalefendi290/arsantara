@extends('layouts.admin')

@section('admin_content')
<div>
    <h1 class="text-2xl font-bold mb-6">Kelola Carousel & Hero Section</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded border border-blue-100 bg-blue-50 p-4 text-sm text-blue-950">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-blue-900">Panduan Cepat Mengatur Carousel</h2>
                <p class="mt-1 leading-6 text-blue-800">
                    Gunakan halaman ini untuk mengganti gambar utama di setiap halaman dan mengatur slider promosi di beranda.
                    Jika sebuah slide dinonaktifkan, data tetap tersimpan tetapi tidak tampil untuk pengunjung.
                </p>
            </div>
            <div class="rounded bg-white/70 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-blue-700">
                Ukuran gambar maksimal 4 MB
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded border border-blue-100 bg-white p-3">
                <p class="font-semibold text-blue-900">1. Pilih jenis slide</p>
                <p class="mt-1 leading-6 text-blue-800">
                    Hero Section tampil sebagai gambar besar di bagian atas halaman. Carousel Konten tampil sebagai slider di bawah section utama beranda.
                </p>
            </div>
            <div class="rounded border border-blue-100 bg-white p-3">
                <p class="font-semibold text-blue-900">2. Isi data dan urutan</p>
                <p class="mt-1 leading-6 text-blue-800">
                    Gunakan angka urutan kecil agar slide muncul lebih dulu. Contoh: urutan 0 tampil sebelum urutan 1.
                </p>
            </div>
            <div class="rounded border border-blue-100 bg-white p-3">
                <p class="font-semibold text-blue-900">3. Simpan dan cek halaman</p>
                <p class="mt-1 leading-6 text-blue-800">
                    Klik tombol simpan, lalu buka halaman terkait. Untuk menyembunyikan slide sementara, hilangkan centang Aktif.
                </p>
            </div>
        </div>

        <details class="mt-4 rounded border border-blue-100 bg-white p-3">
            <summary class="cursor-pointer font-semibold text-blue-900">Lihat penjelasan setiap isian</summary>
            <div class="mt-3 grid grid-cols-1 gap-3 text-blue-800 md:grid-cols-2">
                <p><span class="font-semibold text-blue-900">Halaman:</span> menentukan lokasi hero yang akan diganti, misalnya Beranda, Mobil, FAQ, atau Tentang Kami.</p>
                <p><span class="font-semibold text-blue-900">Gambar:</span> upload foto/banner landscape rasio 16:9, idealnya 1920 x 1080. Website akan menjaga gambar utama tetap utuh tanpa crop.</p>
                <p><span class="font-semibold text-blue-900">Label:</span> teks kecil di atas judul hero, misalnya "Arsantara Properti".</p>
                <p><span class="font-semibold text-blue-900">Judul dan teks:</span> kalimat utama yang dibaca pengunjung di hero section.</p>
                <p><span class="font-semibold text-blue-900">Warna teks hero:</span> mengatur warna label, judul, dan deskripsi. Pilih warna yang kontras dengan gambar agar tetap mudah dibaca.</p>
                <p><span class="font-semibold text-blue-900">Tombol hero:</span> isi maksimal dua tombol per slide. Setiap slide bisa punya label dan link tombol yang berbeda.</p>
                <p><span class="font-semibold text-blue-900">Link tujuan:</span> khusus Carousel Konten. Isi dengan halaman tujuan seperti /pinjaman-dana atau link lengkap https://...</p>
                <p><span class="font-semibold text-blue-900">Aktif:</span> centang berarti tampil di website. Tidak dicentang berarti slide disimpan sebagai draft.</p>
            </div>
        </details>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-bold text-lg mb-1">Tambah Hero Section</h2>
            <p class="text-sm text-gray-500 mb-4">Atur gambar, label, judul, dan teks hero untuk halaman tertentu.</p>

            <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="placement" value="hero">

                <select name="page_key" class="w-full border p-2 rounded" required>
                    <option value="">Pilih halaman</option>
                    @foreach($heroPages as $key => $label)
                        <option value="{{ $key }}">{{ $label }} ({{ $key }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500">Pilih halaman yang gambar utamanya ingin diganti.</p>

                <input type="file" name="image" class="w-full border p-2 rounded" required>
                <p class="text-xs text-gray-500">Gunakan gambar 1920 x 1080 atau rasio 16:9. Tampilan website menjaga gambar utama tetap utuh dan rasionya konsisten.</p>
                <input type="text" name="label" placeholder="Label kecil, contoh: Arsantara Properti" class="w-full border p-2 rounded">
                <input type="text" name="title" placeholder="Judul hero" class="w-full border p-2 rounded" required>
                <textarea name="text" rows="3" placeholder="Teks deskripsi hero" class="w-full border p-2 rounded"></textarea>

                <div class="rounded border border-gray-200 p-3">
                    <p class="mb-2 text-sm font-semibold text-gray-800">Tombol Hero Slide Ini</p>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-[1fr_1.4fr_auto]">
                        <input type="text" name="buttons[0][label]" placeholder="Label tombol 1, contoh: Hubungi Admin" class="border p-2 rounded text-sm">
                        <input type="text" name="buttons[0][url]" placeholder="Link tombol 1, contoh: https://wa.me/628..." class="border p-2 rounded text-sm">
                        <select name="buttons[0][variant]" class="border p-2 rounded text-sm">
                            <option value="primary">Utama</option>
                            <option value="secondary">Outline</option>
                        </select>
                    </div>
                    <div class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-[1fr_1.4fr_auto]">
                        <input type="text" name="buttons[1][label]" placeholder="Label tombol 2, contoh: Daftar sebagai Agen" class="border p-2 rounded text-sm">
                        <input type="text" name="buttons[1][url]" placeholder="Link tombol 2, contoh: /upgrade" class="border p-2 rounded text-sm">
                        <select name="buttons[1][variant]" class="border p-2 rounded text-sm">
                            <option value="secondary">Outline</option>
                            <option value="primary">Utama</option>
                        </select>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Kosongkan jika slide ini tidak memakai tombol. Tiap slide bisa punya tombol berbeda.</p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <label class="rounded border p-2 text-sm">
                        <span class="mb-2 block text-gray-600">Warna Label</span>
                        <input type="color" name="label_color" value="#dbeafe" class="h-10 w-full cursor-pointer rounded">
                    </label>
                    <label class="rounded border p-2 text-sm">
                        <span class="mb-2 block text-gray-600">Warna Judul</span>
                        <input type="color" name="title_color" value="#ffffff" class="h-10 w-full cursor-pointer rounded">
                    </label>
                    <label class="rounded border p-2 text-sm">
                        <span class="mb-2 block text-gray-600">Warna Deskripsi</span>
                        <input type="color" name="text_color" value="#e2e8f0" class="h-10 w-full cursor-pointer rounded">
                    </label>
                </div>
                <p class="text-xs text-gray-500">Pilih warna teks yang tetap terlihat jelas di atas gambar hero.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="number" name="sort_order" value="0" min="0" placeholder="Urutan" class="border p-2 rounded">
                    <label class="flex items-center gap-2 rounded border p-2">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Aktif</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500">Kolom Urutan adalah nomor posisi slide. Angka paling kecil tampil lebih dulu, misalnya 0 tampil sebelum 1, 1 tampil sebelum 2. Centang Aktif agar slide langsung tampil.</p>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Hero</button>
            </form>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h2 class="font-bold text-lg mb-1">Tambah Carousel Konten</h2>
            <p class="text-sm text-gray-500 mb-4">Carousel konten yang muncul di bawah section utama beranda.</p>

            <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="placement" value="content">

                <input type="file" name="image" class="w-full border p-2 rounded" required>
                <p class="text-xs text-gray-500">Carousel konten tampil di beranda. Pakai gambar banner yang mudah dibaca.</p>
                <input type="text" name="title" placeholder="Judul (opsional)" class="w-full border p-2 rounded">
                <input type="text" name="link_url" placeholder="Link tujuan, contoh: /pinjaman-dana" class="w-full border p-2 rounded">
                <p class="text-xs text-gray-500">Link tujuan boleh berupa /nama-halaman, #bagian-halaman, atau URL lengkap.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="number" name="sort_order" value="0" min="0" placeholder="Urutan" class="border p-2 rounded">
                    <label class="flex items-center gap-2 rounded border p-2">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Aktif</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500">Kolom Urutan adalah nomor posisi slide. Angka paling kecil tampil lebih dulu, misalnya 0 tampil sebelum 1, 1 tampil sebelum 2.</p>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">Upload Carousel</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded p-4 mb-8">
        <h2 class="font-bold text-lg mb-4">Hero Section Per Halaman</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($heroCarousels as $c)
                @php
                    $heroButtons = collect($c->buttons ?: [])->values();
                    $buttonOne = $heroButtons->get(0, []);
                    $buttonTwo = $heroButtons->get(1, []);
                @endphp
                <div class="border rounded p-3">
                    <div class="mb-3 aspect-video w-full overflow-hidden rounded bg-gray-100">
                        <img src="{{ asset('storage/'.$c->image) }}" class="h-full w-full object-contain" alt="{{ $c->title }}">
                    </div>

                    <form action="{{ route('carousel.update',$c->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="placement" value="hero">

                        <select name="page_key" class="w-full border p-2 rounded text-sm" required>
                            @foreach($heroPages as $key => $label)
                                <option value="{{ $key }}" {{ $c->page_key === $key ? 'selected' : '' }}>{{ $label }} ({{ $key }})</option>
                            @endforeach
                        </select>

                        <input type="file" name="image" class="w-full border p-2 rounded text-sm">
                        <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengganti gambar.</p>
                        <input type="text" name="label" value="{{ $c->label }}" placeholder="Label" class="w-full border p-2 rounded text-sm">
                        <input type="text" name="title" value="{{ $c->title }}" placeholder="Judul" class="w-full border p-2 rounded text-sm" required>
                        <textarea name="text" rows="3" placeholder="Teks" class="w-full border p-2 rounded text-sm">{{ $c->text }}</textarea>

                        <div class="rounded border border-gray-200 p-2">
                            <p class="mb-2 text-xs font-semibold text-gray-700">Tombol Hero Slide Ini</p>
                            <input type="text" name="buttons[0][label]" value="{{ $buttonOne['label'] ?? '' }}" placeholder="Label tombol 1" class="mb-2 w-full border p-2 rounded text-sm">
                            <input type="text" name="buttons[0][url]" value="{{ $buttonOne['url'] ?? '' }}" placeholder="Link tombol 1" class="mb-2 w-full border p-2 rounded text-sm">
                            <select name="buttons[0][variant]" class="mb-2 w-full border p-2 rounded text-sm">
                                <option value="primary" {{ ($buttonOne['variant'] ?? 'primary') === 'primary' ? 'selected' : '' }}>Utama</option>
                                <option value="secondary" {{ ($buttonOne['variant'] ?? 'primary') === 'secondary' ? 'selected' : '' }}>Outline</option>
                            </select>

                            <input type="text" name="buttons[1][label]" value="{{ $buttonTwo['label'] ?? '' }}" placeholder="Label tombol 2" class="mb-2 w-full border p-2 rounded text-sm">
                            <input type="text" name="buttons[1][url]" value="{{ $buttonTwo['url'] ?? '' }}" placeholder="Link tombol 2" class="mb-2 w-full border p-2 rounded text-sm">
                            <select name="buttons[1][variant]" class="w-full border p-2 rounded text-sm">
                                <option value="secondary" {{ ($buttonTwo['variant'] ?? 'secondary') === 'secondary' ? 'selected' : '' }}>Outline</option>
                                <option value="primary" {{ ($buttonTwo['variant'] ?? 'secondary') === 'primary' ? 'selected' : '' }}>Utama</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                            <label class="rounded border p-2 text-xs text-gray-600">
                                <span class="mb-1 block">Warna Label</span>
                                <input type="color" name="label_color" value="{{ $c->label_color ?: '#dbeafe' }}" class="h-9 w-full cursor-pointer rounded">
                            </label>
                            <label class="rounded border p-2 text-xs text-gray-600">
                                <span class="mb-1 block">Warna Judul</span>
                                <input type="color" name="title_color" value="{{ $c->title_color ?: '#ffffff' }}" class="h-9 w-full cursor-pointer rounded">
                            </label>
                            <label class="rounded border p-2 text-xs text-gray-600">
                                <span class="mb-1 block">Warna Deskripsi</span>
                                <input type="color" name="text_color" value="{{ $c->text_color ?: '#e2e8f0' }}" class="h-9 w-full cursor-pointer rounded">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Warna ini hanya berlaku untuk teks hero di halaman yang dipilih.</p>

                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="sort_order" value="{{ $c->sort_order }}" min="0" class="border p-2 rounded text-sm">
                            <label class="flex items-center gap-2 rounded border p-2 text-sm">
                                <input type="checkbox" name="is_active" value="1" {{ $c->is_active ? 'checked' : '' }}>
                                <span>Aktif</span>
                            </label>
                        </div>

                        <button class="bg-blue-600 text-white w-full py-2 rounded">Simpan Hero</button>
                    </form>

                    <form action="{{ route('carousel.delete',$c->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white w-full mt-2 py-2 rounded"
                            data-swal-confirm="Hapus slide hero ini?">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400">Belum ada hero dinamis. Halaman masih memakai hero bawaan.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white shadow rounded p-4">
        <h2 class="font-bold text-lg mb-4">Carousel Konten Beranda</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($contentCarousels as $c)
                <div class="border rounded p-2">
                    <div class="mb-2 aspect-video w-full overflow-hidden rounded bg-gray-100">
                        <img src="{{ asset('storage/'.$c->image) }}" class="h-full w-full object-contain" alt="{{ $c->title }}">
                    </div>

                    <form action="{{ route('carousel.update',$c->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="placement" value="content">

                        <input type="file" name="image" class="w-full border p-2 rounded text-sm">
                        <p class="text-xs text-gray-500">Kosongkan jika gambar lama masih dipakai.</p>
                        <input type="text" name="title" value="{{ $c->title }}" placeholder="Judul (opsional)" class="w-full border p-2 rounded text-sm">
                        <input type="text" name="link_url" value="{{ $c->link_url }}" placeholder="Link tujuan" class="w-full border p-2 rounded text-sm">

                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="sort_order" value="{{ $c->sort_order }}" min="0" class="border p-2 rounded text-sm">
                            <label class="flex items-center gap-2 rounded border p-2 text-sm">
                                <input type="checkbox" name="is_active" value="1" {{ $c->is_active ? 'checked' : '' }}>
                                <span>Aktif</span>
                            </label>
                        </div>

                        <button class="bg-blue-600 text-white w-full py-2 rounded">Simpan Carousel</button>
                    </form>

                    <form action="{{ route('carousel.delete',$c->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white w-full mt-2 py-2 rounded"
                            data-swal-confirm="Hapus carousel ini?">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400">Belum ada carousel konten.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
