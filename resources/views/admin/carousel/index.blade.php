@extends('layouts.admin')

@section('admin_content')
<div>

    <h1 class="text-2xl font-bold mb-6">Kelola Carousel</h1>

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

    <!-- FORM UPLOAD -->
    <div class="bg-white shadow rounded p-4 mb-6">
        <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="file" name="image" class="border p-2 rounded" required>

                <input type="text" name="title" placeholder="Judul (opsional)"
                    class="border p-2 rounded">

                <input type="text" name="link_url" placeholder="Link tujuan, contoh: /pinjaman-dana"
                    class="border p-2 rounded">

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Upload
                </button>
            </div>
        </form>
    </div>

    <!-- LIST DATA -->
    <div class="bg-white shadow rounded p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            @foreach($carousels as $c)
            <div class="border rounded p-2">

                <div class="mb-2 aspect-video w-full overflow-hidden rounded bg-gray-100">
                    <img src="{{ asset('storage/'.$c->image) }}"
                        class="h-full w-full object-contain">
                </div>

                <form action="{{ route('carousel.update',$c->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    @method('PATCH')

                    <input type="file" name="image" class="w-full border p-2 rounded text-sm">

                    <input type="text" name="title" value="{{ $c->title }}"
                        placeholder="Judul (opsional)"
                        class="w-full border p-2 rounded text-sm">

                    <input type="text" name="link_url" value="{{ $c->link_url }}"
                        placeholder="Link tujuan"
                        class="w-full border p-2 rounded text-sm">

                    @if($c->link_url)
                        <a href="{{ $c->link_url }}" target="_blank"
                            class="block truncate text-xs text-blue-600 hover:underline">
                            {{ $c->link_url }}
                        </a>
                    @else
                        <p class="text-xs text-gray-400">Belum ada link tujuan</p>
                    @endif

                    <button class="bg-blue-600 text-white w-full py-1 rounded">
                        Simpan Perubahan
                    </button>
                </form>

                <form action="{{ route('carousel.delete',$c->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 text-white w-full mt-2 py-1 rounded">
                        Hapus
                    </button>
                </form>

            </div>
            @endforeach

        </div>
    </div>
</div>
@endsection
