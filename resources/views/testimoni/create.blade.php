@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6 max-w-2xl">

    <h1 class="text-2xl font-bold mb-6 text-center">
        Tulis Testimoni
    </h1>

    <!-- ERROR -->
    @if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="text-sm">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- FORM -->
    <form action="{{ route('testimoni.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="bg-white p-6 rounded-2xl shadow space-y-5">

        @csrf

        <!-- NAMA -->
        <div>
            <label class="block mb-1 font-medium">Nama</label>
            <input type="text" name="name" 
                value="{{ old('name') }}"
                class="w-full border p-3 rounded-lg focus:ring focus:ring-blue-200"
                placeholder="Masukkan nama Anda">
        </div>

        <!-- PEKERJAAN (OPSIONAL) -->
        <div>
            <label class="block mb-1 font-medium">Pekerjaan (opsional)</label>
            <input type="text" name="job"
                value="{{ old('job') }}"
                class="w-full border p-3 rounded-lg"
                placeholder="Contoh: Mahasiswa / Karyawan">
        </div>

        <!-- FOTO -->
        <div>
            <label class="block mb-1 font-medium">Foto (opsional)</label>
            <input type="file" name="photo"
                class="w-full border p-2 rounded-lg">
        </div>

        <!-- RATING -->
        <div>
            <label class="block mb-2 font-medium">Rating</label>

            <div class="flex gap-2 text-3xl text-gray-300 cursor-pointer" id="rating">
                @for($i=1; $i<=5; $i++)
                    <span data-value="{{ $i }}">★</span>
                @endfor
            </div>

            <input type="hidden" name="rating" id="ratingInput">
        </div>

        <!-- KOMENTAR -->
        <div>
            <label class="block mb-1 font-medium">Komentar</label>
            <textarea name="message" rows="4"
                class="w-full border p-3 rounded-lg"
                placeholder="Tulis pengalaman Anda...">{{ old('message') }}</textarea>
        </div>

        <!-- BUTTON -->
        <button class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
            Kirim Testimoni
        </button>

    </form>

</div>

<!-- SCRIPT RATING -->
<script>
const stars = document.querySelectorAll('#rating span');
const input = document.getElementById('ratingInput');

stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        let value = star.getAttribute('data-value');
        input.value = value;

        stars.forEach((s, i) => {
            s.classList.remove('text-yellow-400');
            if(i < value){
                s.classList.add('text-yellow-400');
            }
        });
    });
});
</script>

@endsection