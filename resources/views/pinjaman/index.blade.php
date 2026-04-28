@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-16 px-6">

    <!-- TITLE -->
    <h1 class="text-3xl font-bold mb-6">
        Pinjaman Dana Jaminan BPKB
    </h1>

    <!-- CARD -->
    <div class="bg-white shadow rounded-xl p-6">

        <!-- DESKRIPSI -->
        <p class="text-gray-600 mb-6">
            Ajukan pinjaman dana dengan proses cepat, aman, dan terpercaya.
            Silakan baca syarat dan ketentuan berikut sebelum melanjutkan.
        </p>

        <!-- ================= SYARAT ================= -->
        <div class="bg-gray-50 border rounded-lg p-4 h-64 overflow-y-auto text-sm text-gray-700 space-y-3">

            <p><b>1.</b> Pemohon wajib berusia minimal 21 tahun.</p>
            <p><b>2.</b> Memiliki KTP yang masih berlaku.</p>
            <p><b>3.</b> Kendaraan atas nama sendiri / keluarga.</p>
            <p><b>4.</b> BPKB asli tersedia sebagai jaminan.</p>
            <p><b>5.</b> STNK aktif dan tidak dalam sengketa.</p>
            <p><b>6.</b> Bersedia mengikuti proses survey dari tim kami.</p>
            <p><b>7.</b> Data yang diberikan harus valid dan benar.</p>

            <p class="pt-2 text-gray-500">
                * Dengan melanjutkan, Anda menyetujui bahwa data akan digunakan untuk proses pengajuan pinjaman.
            </p>

        </div>

        <!-- ================= CHECKBOX ================= -->
        <div class="mt-6 flex items-center gap-2">
            <input type="checkbox" id="agree" class="w-4 h-4">
            <label for="agree" class="text-sm text-gray-700">
                Saya telah membaca dan menyetujui syarat & ketentuan
            </label>
        </div>

        <!-- ================= BUTTON ================= -->
        @php
            $phone = "62895347042844";
            $message = urlencode("Halo Admin Arsantara 👋\nSaya ingin mengajukan pinjaman dana, mohon informasinya.");
        @endphp

        <button id="btnWA"
            onclick="goWA()"
            disabled
            class="mt-6 w-full bg-green-500 text-white py-3 rounded-lg font-semibold opacity-50 cursor-not-allowed transition">

            Ajukan via WhatsApp
        </button>

    </div>

</div>

<script>
const checkbox = document.getElementById('agree');
const button = document.getElementById('btnWA');

checkbox.addEventListener('change', function(){
    if(this.checked){
        button.disabled = false;
        button.classList.remove('opacity-50','cursor-not-allowed');
    } else {
        button.disabled = true;
        button.classList.add('opacity-50','cursor-not-allowed');
    }
});

function goWA(){
    const url = "https://wa.me/{{ $phone }}?text={{ $message }}";
    window.open(url, '_blank');
}
</script>

@endsection