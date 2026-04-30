@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow">

    <!-- STEP INDICATOR -->
    <div class="flex justify-between mb-6 text-sm">
        <div id="step1Indicator" class="font-bold text-blue-600">1. Data Diri</div>
        <div id="step2Indicator" class="text-gray-400">2. Data Usaha</div>
        <div id="step3Indicator" class="text-gray-400">3. Konfirmasi</div>
    </div>

    <form id="multiStepForm" method="POST" action="{{ route('submit.request') }}">
        @csrf

        <input type="hidden" name="role" id="roleInput">

        <!-- ================= STEP 1 ================= -->
        <div class="step" id="step1">
            <h2 class="text-lg font-bold mb-4">Data Diri</h2>

            <input type="text" name="nama" placeholder="Nama Lengkap"
                class="w-full border p-2 rounded mb-3" required>

            <input type="text" name="no_hp" placeholder="No HP"
                class="w-full border p-2 rounded mb-3" required>

            <input type="email" name="email" placeholder="Email"
                class="w-full border p-2 rounded mb-3" required>

            <textarea name="alamat" placeholder="Alamat"
                class="w-full border p-2 rounded mb-3" required></textarea>

            <button type="button" onclick="nextStep(2)"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Lanjut
            </button>
        </div>

        <!-- ================= STEP 2 ================= -->
        <div class="step hidden" id="step2">
            <h2 class="text-lg font-bold mb-4">Data Usaha (Agen)</h2>

            <div id="agenFields">
                <input type="text" name="nama_agen" placeholder="Nama Agen"
                    class="w-full border p-2 rounded mb-3">

                <input type="text" name="nama_pemilik_agen" placeholder="Nama Pemilik Agen"
                    class="w-full border p-2 rounded mb-3">
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="prevStep(1)"
                    class="bg-gray-400 text-white px-4 py-2 rounded">
                    Kembali
                </button>

                <button type="button" onclick="nextStep(3)"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                    Lanjut
                </button>
            </div>
        </div>

        <!-- ================= STEP 3 ================= -->
        <div class="step hidden" id="step3">
            <h2 class="text-lg font-bold mb-4">Konfirmasi Data</h2>

            <div id="preview" class="text-sm text-gray-600 space-y-2 mb-4"></div>

            <div class="flex justify-between">
                <button type="button" onclick="prevStep(2)"
                    class="bg-gray-400 text-white px-4 py-2 rounded">
                    Kembali
                </button>

                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Submit
                </button>
            </div>
        </div>

    </form>
</div>

<script>
let currentStep = 1;
let role = 'agen'; // default, nanti bisa kamu set dinamis

function nextStep(step) {
    document.getElementById('step' + currentStep).classList.add('hidden');
    document.getElementById('step' + step).classList.remove('hidden');

    currentStep = step;
    updateIndicator();

    if (step === 3) previewData();
}

function prevStep(step) {
    document.getElementById('step' + currentStep).classList.add('hidden');
    document.getElementById('step' + step).classList.remove('hidden');

    currentStep = step;
    updateIndicator();
}

function updateIndicator() {
    document.querySelectorAll('[id^="step"][id$="Indicator"]').forEach(el => {
        el.classList.remove('text-blue-600','font-bold');
        el.classList.add('text-gray-400');
    });

    document.getElementById('step' + currentStep + 'Indicator')
        .classList.add('text-blue-600','font-bold');
}

function previewData() {
    let form = document.getElementById('multiStepForm');
    let data = new FormData(form);

    let html = '';
    data.forEach((value, key) => {
        if (value) {
            html += `<p><b>${key}</b>: ${value}</p>`;
        }
    });

    document.getElementById('preview').innerHTML = html;
}
</script>
@endsection