<nav data-aos="fade-down" x-data="{ open: false }" class="fixed top-0 left-0 w-full z-50 bg-white shadow border-b border-gray-100 dark:border-gray-700 text-white">
    <!-- Primary Navigation Menu -->
     <div data-aos="fade-down" class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

    <!-- LOGO -->
    <a href="{{ route('home') }}" class="flex items-center space-x-3">
        <span class="text-xl text-gray-900 font-bold">Arsantara Management</span>
    </a>

    <!-- RIGHT (SEARCH + USER) -->
    <div class="flex items-center text-gray-800 gap-3 md:order-2">

        <!-- SEARCH -->
        <div class="relative hidden md:block">
            <input type="text"
                class="w-full ps-3 pe-3 py-2 border rounded text-sm"
                placeholder="Search">
        </div>

        <!-- USER -->
        @auth
        <div class="relative">
            <button onclick="toggleDropdown()" class="px-3 py-2 border rounded">
                {{ auth()->user()->name }}
            </button>

            <div id="dropdownUser" class="hidden absolute right-0 mt-2 w-40 bg-white border rounded shadow">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth

        @guest
        <button data-modal-target="login-modal" data-modal-toggle="login-modal"
            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
            Login
        </button>
        @endguest

        <!-- HAMBURGER -->
        <button onclick="toggleMenu()" class="md:hidden p-2 border rounded">
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
            <button id="dropdownKategoriButton"
                data-dropdown-toggle="dropdownKategori"
                data-dropdown-trigger="hover"
                class="flex items-center gap-1 py-2 px-3 text-gray-800 hover:text-blue-500 font-medium">

                Kategori
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="m19 9-7 7-7-7"/>
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

<!-- LOGIN MODAL -->
<div id="login-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black/50 backdrop-blur-sm">

    <div class="relative w-full max-w-md p-4">

        <div class="bg-white rounded-xl shadow-lg p-6">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h3 id="modal-title" class="text-lg font-semibold">Login</h3>

                <button data-modal-hide="login-modal"
                    class="text-gray-400 hover:text-black text-xl">
                    ✕
                </button>
            </div>

            <!-- ================= LOGIN FORM ================= -->
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
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
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="w-5 h-5">

                        <span class="text-sm font-medium text-gray-700">
                            Login dengan Google
                        </span>
                    </a>
                </div>
                <!-- SWITCH -->
                <p class="text-sm text-center mt-4">
                    Belum punya akun?
                    <button type="button" onclick="showRegister()"
                        class="text-blue-600 hover:underline">
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
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                    Daftar
                </button>

                <div class="mt-4">
                    <a href="{{ route('google.login') }}"
                        class="flex items-center justify-center gap-3 border border-gray-300 py-2 rounded-lg hover:bg-gray-100 transition">

                        <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="w-5 h-5">

                        <span class="text-sm font-medium text-gray-700">
                            Daftar dengan Google
                        </span>
                    </a>
                </div>

                <!-- SWITCH -->
                <p class="text-sm text-center mt-4">
                    Sudah punya akun?
                    <button type="button" onclick="showLogin()"
                        class="text-blue-600 hover:underline">
                        Login
                    </button>
                </p>
            </form>

        </div>

    </div>
</div>

<script>
function toggleMenu(){
    document.getElementById('menu').classList.toggle('hidden');
}

function toggleDropdown(){
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
</script>
