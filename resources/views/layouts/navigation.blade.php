<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
     <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">

    <!-- LOGO -->
    <a href="{{ route('home') }}" class="flex items-center space-x-3">
        <span class="text-xl font-bold">Marketplace</span>
    </a>

    <!-- RIGHT (SEARCH + USER) -->
    <div class="flex items-center gap-3 md:order-2">

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
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth

        @guest
        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white rounded">
            Login
        </a>
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
                <a href="{{ route('home') }}" class="block py-2 px-3 hover:text-blue-500">
                    Home
                </a>
            </li>

            @auth
                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 hover:text-blue-500">
                        Admin
                    </a>
                </li>
                @endif
            @endauth

            <li>
                <a href="#" class="block py-2 px-3 hover:text-blue-500">
                    Kategori
                </a>
            </li>

        </ul>
    </div>

  </div>

</nav>

<script>
function toggleMenu(){
    document.getElementById('menu').classList.toggle('hidden');
}

function toggleDropdown(){
    document.getElementById('dropdownUser').classList.toggle('hidden');
}
</script>
