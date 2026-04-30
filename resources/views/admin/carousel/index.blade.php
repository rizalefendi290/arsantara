@extends('layouts.admin')

@section('admin_content')
<div>

    <h1 class="text-2xl font-bold mb-6">Kelola Carousel</h1>

    <!-- FORM UPLOAD -->
    <div class="bg-white shadow rounded p-4 mb-6">
        <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-3 gap-4">
                <input type="file" name="image" class="border p-2 rounded" required>

                <input type="text" name="title" placeholder="Judul (opsional)"
                    class="border p-2 rounded">

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Upload
                </button>
            </div>
        </form>
    </div>

    <!-- LIST DATA -->
    <div class="bg-white shadow rounded p-4">
        <div class="grid grid-cols-4 gap-4">

            @foreach($carousels as $c)
            <div class="border rounded p-2">

                <img src="{{ asset('storage/'.$c->image) }}"
                    class="h-32 w-full object-cover rounded mb-2">

                <p class="text-sm text-gray-600">
                    {{ $c->title ?? '-' }}
                </p>

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
