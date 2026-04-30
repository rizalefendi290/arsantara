<!-- MODAL OVERLAY -->
<div id="upgradeModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60">

    <!-- CONTENT -->
    <div onclick="event.stopPropagation()" class="bg-white w-full max-w-2xl mx-4 p-6 rounded-xl shadow-lg relative">

        <!-- CLOSE -->
        <button onclick="closeUpgradeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-black text-xl">
            ✕
        </button>
        <div class="mt-2">

            <!-- STEP -->
            <div class="flex justify-between mb-6 text-sm">
                <div id="step1Indicator" class="font-bold text-blue-600">1. Data Diri</div>
                <div id="step2Indicator" class="text-gray-400">2. Data Usaha</div>
                <div id="step3Indicator" class="text-gray-400">3. Konfirmasi</div>
            </div>

            <!-- PILIH ROLE -->
            <div class="flex gap-3 mb-4">
                <button type="button" id="btnAgen" onclick="setRole('agen')"
                    class="flex-1 border-2 border-blue-600 text-blue-600 py-2 rounded transition">
                    Daftar sebagai Agen
                </button>

                <button type="button" id="btnPemilik" onclick="setRole('pemilik')"
                    class="flex-1 border-2 border-green-600 text-green-600 py-2 rounded transition">
                    Pemilik Produk
                </button>
            </div>

            <!-- FEEDBACK TEXT -->
            <div id="roleInfo" class="hidden mb-4 p-3 rounded-lg text-sm bg-blue-50 text-blue-700">
            </div>

            <form id="multiStepForm" method="POST" action="{{ route('submit.request') }}">
                @csrf
                <input type="hidden" name="role" id="roleInput">

                <!-- STEP 1 -->
                <div class="step" id="step1">
                    <h2 class="text-lg font-bold mb-4">Data Diri</h2>

                    <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full border p-2 rounded mb-3" required>

                    <input type="tel" name="no_hp" placeholder="No HP (08xxxx)" class="w-full border p-2 rounded mb-3" required>

                    <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded mb-3" required>

                    <textarea name="alamat" placeholder="Alamat" class="w-full border p-2 rounded mb-3" required></textarea>

                    <button type="button" onclick="nextStep(2)" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Lanjut
                    </button>
                </div>

                <!-- STEP 2 -->
                <div class="step hidden" id="step2">
                    <h2 class="text-lg font-bold mb-4">Data Usaha</h2>

                    <input type="text" name="nama_agen" placeholder="Nama Agen" class="w-full border p-2 rounded mb-3" required>

                    <input type="text" name="nama_pemilik_agen" placeholder="Nama Pemilik Agen"
                        class="w-full border p-2 rounded mb-3" required>

                    <div class="flex justify-between">
                        <button type="button" onclick="prevStep(1)" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Kembali
                        </button>

                        <button type="button" onclick="nextStep(3)" class="bg-blue-600 text-white px-4 py-2 rounded">
                            Lanjut
                        </button>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="step hidden" id="step3">
                    <h2 class="text-lg font-bold mb-4">Konfirmasi</h2>

                    <div id="preview" class="text-sm text-gray-600 mb-4"></div>

                    <div class="flex justify-between">
                        <button type="button" onclick="prevStep(2)" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Kembali
                        </button>

                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                            Submit
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentStep = 1;
let role = null;

// OPEN MODAL
function openUpgradeModal(selectedRole) {
    const modal = document.getElementById('upgradeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setRole(selectedRole);
}

// CLOSE MODAL
function closeUpgradeModal() {
    const modal = document.getElementById('upgradeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// SET ROLE
function setRole(selectedRole) {
    role = selectedRole;
    document.getElementById('roleInput').value = role;

    let btnAgen = document.getElementById('btnAgen');
    let btnPemilik = document.getElementById('btnPemilik');
    let info = document.getElementById('roleInfo');

    // RESET STYLE
    btnAgen.classList.remove('bg-blue-600', 'text-white');
    btnPemilik.classList.remove('bg-green-600', 'text-white');

    // ACTIVE STYLE + FEEDBACK
    if (role === 'agen') {
        btnAgen.classList.add('bg-blue-600', 'text-white');

        info.classList.remove('hidden');
        info.classList.remove('bg-green-50','text-green-700');
        info.classList.add('bg-blue-50','text-blue-700');
        info.innerHTML = "✅ Anda memilih sebagai <b>Agen</b>. Silakan lanjut isi data usaha.";

        document.getElementById('step2Indicator').style.display = 'block';

    } else {
        btnPemilik.classList.add('bg-green-600', 'text-white');

        info.classList.remove('hidden');
        info.classList.remove('bg-blue-50','text-blue-700');
        info.classList.add('bg-green-50','text-green-700');
        info.innerHTML = "✅ Anda memilih sebagai <b>Pemilik Produk</b>. Data akan langsung dikirim.";

        document.getElementById('step2Indicator').style.display = 'none';
    }

    // OPTIONAL: animasi kecil biar terasa responsif
    info.classList.add('animate-pulse');
    setTimeout(() => info.classList.remove('animate-pulse'), 500);
}

// VALIDASI
function validateStep1() {
    let form = document.getElementById('multiStepForm');

    let nama = form.querySelector('[name="nama"]').value.trim();
    let hp = form.querySelector('[name="no_hp"]').value.trim();
    let email = form.querySelector('[name="email"]').value.trim();
    let alamat = form.querySelector('[name="alamat"]').value.trim();

    if (!role) {
        Swal.fire("Error", "Pilih role terlebih dahulu!", "error");
        return false;
    }

    if (!nama || !hp || !email || !alamat) {
        Swal.fire("Error", "Semua data wajib diisi!", "error");
        return false;
    }

    if (!/^[0-9]+$/.test(hp)) {
        Swal.fire("Error", "No HP harus angka!", "error");
        return false;
    }

    if (hp.length < 10) {
        Swal.fire("Error", "No HP tidak valid!", "error");
        return false;
    }

    return true;
}

// NEXT STEP
function nextStep(step) {
    if (currentStep === 1 && !validateStep1()) return;

    if (role === 'pemilik') {
        document.getElementById('multiStepForm').submit();
        return;
    }

    document.getElementById('step' + currentStep).classList.add('hidden');
    document.getElementById('step' + step).classList.remove('hidden');

    currentStep = step;
    updateIndicator();

    if (step === 3) previewData();
}

// PREV STEP
function prevStep(step) {
    document.getElementById('step' + currentStep).classList.add('hidden');
    document.getElementById('step' + step).classList.remove('hidden');

    currentStep = step;
    updateIndicator();
}

// STEP UI
function updateIndicator() {
    document.querySelectorAll('[id^="step"][id$="Indicator"]').forEach(el => {
        el.classList.remove('text-blue-600', 'font-bold');
        el.classList.add('text-gray-400');
    });

    document.getElementById('step' + currentStep + 'Indicator')
        .classList.add('text-blue-600', 'font-bold');
}

// PREVIEW
function previewData() {
    let form = document.getElementById('multiStepForm');
    let data = new FormData(form);

    let html = '';

    data.forEach((value, key) => {
        if (!value || key === '_token') return;

        // 🔥 UBAH ROLE JADI STATUS
        if (key === 'role') {
            let label = value === 'agen' ? 'Agen' :
                        value === 'pemilik' ? 'Pemilik Produk' :
                        'User';

            html += `<p><b>Status</b>: ${label}</p>`;
        }

        // 🔥 BIAR LEBIH RAPI (rename field)
        else if (key === 'nama') {
            html += `<p><b>Nama</b>: ${value}</p>`;
        }
        else if (key === 'no_hp') {
            html += `<p><b>No HP</b>: ${value}</p>`;
        }
        else if (key === 'nama_agen') {
            html += `<p><b>Nama Agen</b>: ${value}</p>`;
        }
        else if (key === 'nama_pemilik_agen') {
            html += `<p><b>Pemilik Agen</b>: ${value}</p>`;
        }
        else {
            html += `<p><b>${key}</b>: ${value}</p>`;
        }
    });

    document.getElementById('preview').innerHTML = html;
}
</script>