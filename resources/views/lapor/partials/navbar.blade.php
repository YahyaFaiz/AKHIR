<header x-data="{ mnav: false, userOpen: false }" x-init="$watch('mnav', v => document.body.classList.toggle('overflow-hidden', v));" @resize.window="if (window.innerWidth >= 640) { mnav = false }"
    @keydown.window.escape="mnav = false; userOpen = false" class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4 gap-3">

            <!-- Kiri: Hamburger (mobile) + Logo -->
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile) -->
                <button type="button" @click="mnav = true"
                    class="sm:hidden inline-flex items-center justify-center rounded-md border border-gray-300 px-3 py-2 text-gray-700 hover:bg-gray-100 transition"
                    aria-label="Open menu" :aria-expanded="mnav">
                    <svg x-show="!mnav" class="size-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mnav" x-cloak class="size-5" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}"
                    class="text-xl font-bold text-gray-800 tracking-wider flex items-center gap-2">
                    TA
                </a>
            </div>

            <!-- Kanan: Nav items + separator + User Dropdown -->
            <div class="flex items-center gap-3">
                <!-- Desktop Nav (tanpa 'Menu', langsung item) -->
                <ul class="hidden sm:flex items-center gap-4 text-sm font-medium">
                    <li>
                        <a href="{{ route('home') }}"
                            class="px-2 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('lapor') }}"
                            class="px-2 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">Lapor</a>
                    </li>
                    <li>
                        <a href="{{ route('validasi') }}"
                            class="px-2 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">Validasi</a>
                    </li>
                </ul>

                <!-- Separator sebelum profil -->
                <span class="hidden sm:block text-gray-300 select-none">|</span>

                <!-- User Dropdown (tetap di navbar) -->
                <div class="relative">
                    @auth
                        <button @click="userOpen = !userOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition"
                            :aria-expanded="userOpen.toString()" aria-haspopup="true">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-600 text-white font-semibold">
                                {{ Str::substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="hidden sm:block max-w-[10rem] truncate">{{ auth()->user()->name }}</span>
                            <svg :class="{ 'rotate-180': userOpen }" class="size-4 transition-transform hidden sm:block"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div x-show="userOpen" x-transition.origin.top.right @click.outside="userOpen = false"
                            class="absolute right-0 mt-2 w-52 bg-white shadow-lg border border-gray-100 rounded-md overflow-hidden z-50"
                            x-cloak>
                            <a href="" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                            <a href="" class="block px-4 py-2 hover:bg-gray-100">Riwayat Laporan</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600">Log
                                    Out</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700 transition">
                            Log in
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Backdrop (mobile sidebar) -->
    <div x-show="mnav" x-transition.opacity x-cloak class="fixed inset-0 bg-transparent z-[60]" @click="mnav = false"
        aria-hidden="true"></div>

    <!-- Sidebar (mobile) -->
    <aside x-show="mnav" x-cloak x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-150" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 z-[70] h-screen w-72 bg-white border-r border-gray-200 shadow-xl sm:hidden"
        role="dialog" aria-modal="true">
        <!-- Header sidebar -->
        <div class="flex items-center justify-between px-4 py-4 border-b">
            <a href="" class="text-lg font-bold text-gray-800 tracking-wider flex items-center gap-2">
                TA
            </a>
            <button @click="mnav = false"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 px-2.5 py-2 text-gray-700 hover:bg-gray-100 transition"
                aria-label="Close menu">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body sidebar: langsung isi -->
        <div class="px-2 py-3">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">Home</a>
            <a href="{{ route('lapor') }}"
                class="mt-1 block px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">Lapor</a>
            <a href="{{ route('validasi') }}"
                class="mt-1 block px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">Validasi</a>
        </div>
    </aside>
</header>
