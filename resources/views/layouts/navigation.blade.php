<nav data-aos="fade-down" x-data="{ open: false }"
    class="fixed top-0 left-0 w-full z-50 bg-white shadow border-b border-gray-100 dark:border-gray-700 text-white">
    <!-- Primary Navigation Menu -->
    <div data-aos="fade-down" class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3">
            <span class="text-xl text-gray-900 font-bold">Arsantara Management</span>
        </a>

        <!-- RIGHT (SEARCH + USER) -->
        <div class="flex items-center text-gray-800 gap-3 md:order-2">

            <!-- SEARCH -->
            <form method="GET" action="{{ route('search') }}" class="relative hidden md:block">
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    class="w-64 ps-3 pe-3 py-2 border rounded text-sm"
                    placeholder="Cari listing atau berita">
            </form>

            <!-- USER -->
            @auth
            @php
                $navUser = auth()->user();
                $navProfilePhoto = $navUser->profile_photo
                    ? asset('storage/'.$navUser->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode($navUser->name).'&background=2563eb&color=fff&bold=true';
            @endphp
            <div class="relative hidden md:block">
                <button type="button" onclick="toggleDropdown()"
                    class="group flex min-w-[74px] flex-col items-center justify-center gap-1 rounded-xl border border-gray-200 bg-white px-2 py-1.5 text-center shadow-sm transition hover:border-blue-200 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    aria-label="Buka menu profil">
                    <img src="{{ $navProfilePhoto }}" alt="{{ $navUser->name }}"
                        class="h-9 w-9 rounded-full border-2 border-white object-cover shadow ring-1 ring-gray-200 transition group-hover:ring-blue-300">
                    <span class="max-w-[82px] truncate text-[11px] font-semibold leading-tight text-gray-700">
                        {{ $navUser->name }}
                    </span>
                </button>

                <div id="dropdownUser" class="hidden absolute right-0 mt-2 w-60 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl z-50">

                    <div class="flex items-center gap-3 border-b border-gray-100 px-4 py-3">
                        <img src="{{ $navProfilePhoto }}" alt="{{ $navUser->name }}"
                            class="h-11 w-11 rounded-full object-cover ring-2 ring-blue-100">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-gray-900">{{ $navUser->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $navUser->email }}</p>
                        </div>
                    </div>

                    <!-- PROFILE -->
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                        Profile
                    </a>

                    <!-- 🔥 KHUSUS USER BIASA -->
                    @if(auth()->user()->role === 'user')
                    <div class="px-4 py-3 border-t border-gray-100">
                        <p class="text-sm font-semibold mb-2">Ingin jual produk?</p>

                        <button type="button" onclick="openUpgradeModal('agen')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition mb-2">
                            Jadi Agen
                        </button>

                        <button type="button" onclick="openUpgradeModal('pemilik')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
                            Pemilik Produk
                        </button>
                    </div>
                    @endif

                    <!-- 🔥 KHUSUS AGEN -->
                    @if(auth()->user()->role === 'agen')
                    <a href="{{ route('agent.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                        Dashboard Agen
                    </a>
                    @endif

                    <!-- 🔥 KHUSUS PEMILIK -->
                    @if(auth()->user()->role === 'pemilik')
                    <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                        Dashboard Pemilik
                    </a>
                    @endif

                    <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 border-t border-gray-100">
                            Logout
                        </button>
                    </form>

                </div>
            </div>
            @endauth
            @guest
            <button data-modal-target="login-modal" data-modal-toggle="login-modal"
                class="hidden px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition md:inline-flex">
                Login
            </button>
            @endguest

            <!-- HAMBURGER -->
            <button type="button" onclick="openMobileMenu()"
                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-gray-200 bg-white text-[0px] text-gray-800 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 md:hidden"
                aria-label="Buka menu navigasi">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                ☰
            </button>
        </div>

        <!-- MENU -->
        <div id="menu" class="hidden w-full md:flex md:w-auto md:order-1">
            <ul class="flex flex-col md:flex-row gap-4 mt-4 md:mt-0">

                <li>
                    <a href="{{ route('home') }}" class="block py-2 px-3 text-gray-800 hover:text-blue-500">
                        Home
                    </a>
                </li>

                @auth
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 text-gray-800 hover:text-blue-500">
                        Admin
                    </a>
                </li>
                @endif
                @endauth

                <li class="relative">

                    <!-- BUTTON -->
                    <button id="dropdownKategoriButton" data-dropdown-toggle="dropdownKategori"
                        data-dropdown-trigger="hover"
                        class="flex items-center gap-1 py-2 px-3 text-gray-800 hover:text-blue-500 font-medium">

                        Kategori
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- DROPDOWN -->
                    <div id="dropdownKategori"
                        class="z-50 hidden absolute mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg">

                        <ul class="p-2 text-sm text-gray-800">

                            <li>
                                <a href="{{ route('mobil.index') }}"
                                    class="block px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-800 hover:text-blue-600 transition">
                                    🚗 Mobil
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('motor.index') }}"
                                    class="block px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-800 hover:text-blue-600 transition">
                                    🏍️ Motor
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('rumah.index') }}"
                                    class="block px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-800 hover:text-blue-600 transition">
                                    🏠 Rumah
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('tanah.index') }}"
                                    class="block px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-800 hover:text-blue-600 transition">
                                    🌱 Tanah
                                </a>
                            </li>

                        </ul>
                    </div>

                </li>

                <li>
                    <a href="{{ route('testimoni.index') }}" class="block py-2 px-3 text-gray-800 hover:text-blue-500">
                        Ulasan
                    </a>
                </li>

                <li>
                    <a href="{{ route('about') }}" class="block py-2 px-3 text-gray-800 hover:text-blue-500">Tentang</a>
                </li>

            </ul>
        </div>

    </div>

</nav>

<!-- MOBILE NAVIGATION MODAL -->
<div id="mobileMenuModal" class="fixed inset-0 z-[60] hidden md:hidden" aria-hidden="true">
    <button type="button" class="absolute inset-0 bg-slate-950/55 backdrop-blur-sm" onclick="closeMobileMenu()"
        aria-label="Tutup menu"></button>

    <aside class="absolute right-0 top-0 flex h-full w-full max-w-sm flex-col overflow-y-auto bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <a href="{{ route('home') }}" class="text-base font-bold text-gray-950" onclick="closeMobileMenu()">
                Arsantara Management
            </a>
            <button type="button" onclick="closeMobileMenu()"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition hover:bg-gray-50"
                aria-label="Tutup menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-5 px-5 py-5">
            @auth
                @php
                    $mobileUser = auth()->user();
                    $mobileProfilePhoto = $mobileUser->profile_photo
                        ? asset('storage/'.$mobileUser->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($mobileUser->name).'&background=2563eb&color=fff&bold=true';
                @endphp
                <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $mobileProfilePhoto }}" alt="{{ $mobileUser->name }}"
                            class="h-16 w-16 rounded-full object-cover ring-4 ring-white shadow">
                        <div class="min-w-0">
                            <p class="truncate text-base font-bold text-gray-950">{{ $mobileUser->name }}</p>
                            <p class="truncate text-sm text-gray-600">{{ $mobileUser->email }}</p>
                            <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ ucfirst($mobileUser->role) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" onclick="closeMobileMenu()"
                        class="mt-4 flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Lihat Profile
                    </a>
                </div>
            @else
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-base font-bold text-gray-950">Masuk ke akun Arsantara</p>
                    <p class="mt-1 text-sm text-gray-600">Kelola profile, simpan listing, dan mulai pasang produk.</p>
                    <button type="button" data-modal-target="login-modal" data-modal-toggle="login-modal"
                        onclick="closeMobileMenu()"
                        class="mt-4 w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Login / Daftar
                    </button>
                </div>
            @endauth

            <form method="GET" action="{{ route('search') }}" class="relative">
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Cari listing atau berita">
            </form>

            <nav class="space-y-2">
                <a href="{{ route('home') }}" onclick="closeMobileMenu()"
                    class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Home</a>
                <a href="{{ route('properti') }}" onclick="closeMobileMenu()"
                    class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Properti</a>
                <a href="{{ route('autoshow') }}" onclick="closeMobileMenu()"
                    class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Autoshow</a>
                <a href="{{ route('testimoni.index') }}" onclick="closeMobileMenu()"
                    class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Ulasan</a>
                <a href="{{ route('about') }}" onclick="closeMobileMenu()"
                    class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Tentang</a>
            </nav>

            <div>
                <p class="mb-3 px-1 text-xs font-bold uppercase tracking-wide text-gray-500">Kategori</p>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('rumah.index') }}" onclick="closeMobileMenu()"
                        class="rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-800 hover:border-blue-200 hover:bg-blue-50">Rumah</a>
                    <a href="{{ route('tanah.index') }}" onclick="closeMobileMenu()"
                        class="rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-800 hover:border-blue-200 hover:bg-blue-50">Tanah</a>
                    <a href="{{ route('mobil.index') }}" onclick="closeMobileMenu()"
                        class="rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-800 hover:border-blue-200 hover:bg-blue-50">Mobil</a>
                    <a href="{{ route('motor.index') }}" onclick="closeMobileMenu()"
                        class="rounded-2xl border border-gray-200 px-4 py-3 text-sm font-semibold text-gray-800 hover:border-blue-200 hover:bg-blue-50">Motor</a>
                </div>
            </div>

            @auth
                <div class="space-y-2 border-t border-gray-100 pt-4">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" onclick="closeMobileMenu()"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Dashboard Admin</a>
                    @endif
                    @if(auth()->user()->role === 'agen')
                        <a href="{{ route('agent.dashboard') }}" onclick="closeMobileMenu()"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Dashboard Agen</a>
                    @endif
                    @if(auth()->user()->role === 'pemilik')
                        <a href="{{ route('owner.dashboard') }}" onclick="closeMobileMenu()"
                            class="block rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-blue-50 hover:text-blue-700">Dashboard Pemilik</a>
                    @endif
                    @if(auth()->user()->role === 'user')
                        <button type="button" onclick="closeMobileMenu(); openUpgradeModal('agen')"
                            class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Jadi Agen</button>
                        <button type="button" onclick="closeMobileMenu(); openUpgradeModal('pemilik')"
                            class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">Pemilik Produk</button>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl border border-red-100 px-4 py-3 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </aside>
</div>

<!-- LOGIN MODAL -->
<div id="login-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/50 backdrop-blur-sm">

    <div class="relative w-full max-w-md p-4">

        <div class="bg-white rounded-xl shadow-lg p-6">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold">Login</h3>

                <button data-modal-hide="login-modal" class="text-gray-400 hover:text-black text-xl">
                    ✕
                </button>
            </div>

            <!-- ================= LOGIN FORM ================= -->
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="flex justify-between items-center mb-4 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    <a href="#" class="text-blue-500 hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                    Login
                </button>

                <div class="mt-4">
                    <a href="{{ route('google.login') }}"
                        class="flex items-center justify-center gap-3 border border-gray-300 py-2 rounded-lg hover:bg-gray-100 transition">

                        <!-- ICON GOOGLE -->
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">

                        <span class="text-sm font-medium text-gray-700">
                            Login dengan Google
                        </span>
                    </a>
                </div>
                <!-- SWITCH -->
                <p class="text-sm text-center mt-4">
                    Belum punya akun?
                    <button type="button" onclick="showRegister()" class="text-blue-600 hover:underline">
                        Daftar
                    </button>
                </p>
            </form>


            <!-- ================= REGISTER FORM ================= -->
            <form id="register-form" method="POST" action="{{ route('register') }}" class="hidden">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm mb-1">Nama</label>
                    <input type="text" name="name"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                    Daftar
                </button>

                <div class="mt-4">
                    <a href="{{ route('google.login') }}"
                        class="flex items-center justify-center gap-3 border border-gray-300 py-2 rounded-lg hover:bg-gray-100 transition">

                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">

                        <span class="text-sm font-medium text-gray-700">
                            Daftar dengan Google
                        </span>
                    </a>
                </div>

                <!-- SWITCH -->
                <p class="text-sm text-center mt-4">
                    Sudah punya akun?
                    <button type="button" onclick="showLogin()" class="text-blue-600 hover:underline">
                        Login
                    </button>
                </p>
            </form>

        </div>

    </div>
</div>

<script>
function openMobileMenu() {
    const modal = document.getElementById('mobileMenuModal');
    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
}

function closeMobileMenu() {
    const modal = document.getElementById('mobileMenuModal');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
}

function toggleDropdown() {
    document.getElementById('dropdownUser').classList.toggle('hidden');
}

function showRegister() {
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('register-form').classList.remove('hidden');
    document.getElementById('modal-title').innerText = 'Register';
}

function showLogin() {
    document.getElementById('register-form').classList.add('hidden');
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('modal-title').innerText = 'Login';
}

function openUpgradeModal(role) {
    const modal = document.getElementById('upgradeModal');
    modal.classList.remove('hidden');

    // set role ke input hidden
    document.getElementById('roleInput').value = role;

    // reset form
    document.getElementById('multiStepForm').reset();

    // reset step
    currentStep = 1;
    document.querySelectorAll('.step').forEach(el => el.classList.add('hidden'));
    document.getElementById('step1').classList.remove('hidden');

    // reset indicator
    updateIndicator();

    // 🔥 handle khusus pemilik
    if (role === 'pemilik') {
        document.getElementById('step2Indicator').style.display = 'none';
    } else {
        document.getElementById('step2Indicator').style.display = 'block';
    }

    // disable scroll background
    document.body.classList.add('overflow-hidden');
}

function closeUpgradeModal() {
    document.getElementById('upgradeModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('upgradeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpgradeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const mobileMenu = document.getElementById('mobileMenuModal');
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            closeMobileMenu();
        }
    }
});
</script>
