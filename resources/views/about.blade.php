@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    <!-- ================= BANNER ================= -->
    <div class="rounded-2xl overflow-hidden mb-10">
        <img src="https://via.placeholder.com/1200x300"
             class="w-full h-[200px] md:h-[300px] object-cover">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- ================= LEFT CONTENT ================= -->
        <div class="lg:col-span-2">

            <h1 class="text-2xl md:text-3xl font-bold mb-4">
                ARSANTARA BISNIS PROPERTI MASA KINI
            </h1>

            <p class="text-gray-600 leading-relaxed mb-4">
                Arsantara hadir sebagai platform digital yang menyediakan solusi lengkap
                untuk kebutuhan otomotif, properti, dan pembiayaan. Kami membantu
                masyarakat dalam proses jual beli, sewa, serta pengajuan kredit dengan
                sistem yang cepat, aman, dan terpercaya.
            </p>

            <p class="text-gray-600 leading-relaxed mb-4">
                Dengan memanfaatkan teknologi digital, Arsantara menghubungkan penjual,
                pembeli, dan mitra bisnis dalam satu ekosistem yang terintegrasi.
                Kami berkomitmen untuk memberikan pengalaman terbaik bagi setiap pengguna.
            </p>

            <p class="text-gray-600 leading-relaxed mb-4">
                Arsantara juga terus berkembang dengan menghadirkan fitur-fitur inovatif
                seperti marketplace properti, layanan pinjaman dana, serta sistem
                pengajuan yang mudah digunakan oleh semua kalangan.
            </p>

            <!-- VISI MISI -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-3">Visi</h2>
                <p class="text-gray-600 mb-4">
                    Menjadi platform digital terpercaya dalam bidang properti dan otomotif di Indonesia.
                </p>

                <h2 class="text-xl font-bold mb-3">Misi</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Menyediakan layanan yang cepat dan transparan</li>
                    <li>Menghubungkan penjual dan pembeli secara efisien</li>
                    <li>Menghadirkan solusi finansial yang mudah diakses</li>
                    <li>Meningkatkan kualitas layanan berbasis teknologi</li>
                </ul>
            </div>

        </div>

        <!-- ================= SIDEBAR ================= -->
        <div>

            <div class="bg-white p-5 rounded-xl shadow">

                <h2 class="text-xl font-bold mb-4 border-b pb-2">
                    Arsantara News
                </h2>

                @forelse($posts as $post)
                <a href="{{ route('post.show', $post->id) }}"
                   class="flex gap-3 mb-4 hover:bg-gray-50 p-2 rounded-lg transition">

                    <img src="{{ $post->images->count() 
                        ? asset('storage/'.$post->images->first()->image) 
                        : 'https://via.placeholder.com/80' }}"
                        class="w-20 h-16 object-cover rounded">

                    <div>
                        <h3 class="text-sm font-semibold line-clamp-2">
                            {{ $post->title }}
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $post->created_at->format('d M Y') }}
                        </p>
                    </div>

                </a>
                @empty
                <p class="text-gray-400">Belum ada berita</p>
                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection