@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-6">Buat Ulasan</h1>

    <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Nama"
            class="w-full border p-3 rounded">

        <textarea name="message" rows="4"
            placeholder="Tulis ulasan..."
            class="w-full border p-3 rounded"></textarea>

        <!-- RATING -->
        <select name="rating" class="w-full border p-3 rounded">
            <option value="">Pilih Rating</option>
            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
            <option value="4">⭐⭐⭐⭐ (4)</option>
            <option value="3">⭐⭐⭐ (3)</option>
            <option value="2">⭐⭐ (2)</option>
            <option value="1">⭐ (1)</option>
        </select>

        <!-- FOTO -->
        <input type="file" name="photo" class="w-full border p-2 rounded">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Kirim Ulasan
        </button>

    </form>

</div>

@endsection