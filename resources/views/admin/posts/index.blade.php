@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Data Berita</h1>
        <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Gambar</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($posts as $post)
                <tr class="border-t">
                    <td class="p-2">
                        <img 
                            src="{{ $post->images->first() 
                                ? asset('storage/'.$post->images->first()->image) 
                                : 'https://via.placeholder.com/80' }}"
                            class="w-20 h-14 object-cover"
                        >
                    </td>

                    <td>{{ $post->title }}</td>

                    <td class="flex gap-2 p-2">
                        <a href="{{ route('posts.edit',$post->id) }}" 
                           class="bg-yellow-400 px-3 py-1 rounded">
                           Edit
                        </a>

                        <form action="{{ route('posts.destroy',$post->id) }}" method="POST">
                            @csrf 
                            @method('DELETE')

                            <button onclick="return confirm('Hapus?')" 
                                class="bg-red-500 text-white px-3 py-1 rounded">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>

</div>
@endsection