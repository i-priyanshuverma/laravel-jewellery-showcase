<nav x-data="{ open: false }" class="bg-white/85 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/80 sticky top-0 z-40 transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex items-center gap-8">
                <!-- Neutral Brand Logo -->
                <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isVendor() ? route('vendor.dashboard') : route('products.index'))) : route('products.index') }}" class="flex items-center gap-3 group">
                    <x-application-logo class="w-10 h-10 shadow-md shadow-amber-500/20 group-hover:scale-105 transition-transform" />
                    <div>
                        <span class="text-base font-black tracking-widest text-slate-900 dark:text-slate-100 block transition-colors">SONAR HAAT</span>
                        <span class="text-[10px] tracking-wider text-amber-600 dark:text-amber-400 uppercase font-bold block -mt-0.5">{{ __('Multi-Vendor Goldsmith Showcase') }}</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-6">
                    @if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isVendor()))
                        <a href="{{ route('products.index') }}" class="px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('products.index') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 dark:border-amber-400' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400' }}">
                            {{ __('Explore Catalogue') }}
                        </a>
                    @endif

                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('admin.*') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 dark:border-amber-400' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400' }}">
                                {{ __('Admin Panel') }}
                            </a>
                        @endif

                        @if (auth()->user()->isVendor())
                            <a href="{{ route('vendor.dashboard') }}" class="px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('vendor.*') ? 'text-amber-600 dark:text-amber-400 border-b-2 border-amber-600 dark:border-amber-400' : 'text-slate-600 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400' }}">
                                {{ __('Vendor Portal') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Actions -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <!-- Search Bar in Top Navbar -->
                @php
                    $searchTargetRoute = route('products.index');
                    if (auth()->check()) {
                        if (auth()->user()->isAdmin()) {
                            $searchTargetRoute = route('admin.products.index');
                        } elseif (auth()->user()->isVendor()) {
                            $searchTargetRoute = route('vendor.products.index');
                        }
                    }
                @endphp

                <div x-data="{
                        isExpanded: {{ request()->filled('search') ? 'true' : 'false' }},
                        query: '{{ addslashes(request('search', '')) }}',
                        expand() {
                            this.isExpanded = true;
                            this.$nextTick(() => this.$refs.navSearchInput.focus());
                        },
                        collapse() {
                            if (!this.query.trim()) {
                                this.isExpanded = false;
                            }
                        }
                    }"
                    @click.outside="collapse()"
                    class="relative flex items-center">

                    <form method="GET" action="{{ $searchTargetRoute }}" class="flex items-center">
                        <div class="relative flex items-center">
                            <input x-ref="navSearchInput"
                                   type="text"
                                   name="search"
                                   x-model="query"
                                   placeholder="{{ __('Search jewellery, SKU, vendor...') }}"
                                   :class="isExpanded ? 'w-64 md:w-80 opacity-100 px-4 py-2 pr-10 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100' : 'w-0 opacity-0 p-0 border-transparent bg-transparent cursor-pointer'"
                                   class="rounded-xl text-xs placeholder-slate-400 dark:placeholder-slate-500 border transition-all duration-300 ease-out focus:ring-1 focus:ring-amber-500 focus:border-amber-500 shadow-inner h-10">

                            <!-- Submit / Trigger Icon Button -->
                            <button type="button"
                                    @click="if (isExpanded && query.trim()) { $el.closest('form').submit(); } else { expand(); }"
                                    :class="isExpanded ? 'absolute right-2 text-amber-600 dark:text-amber-400' : 'w-10 h-10 text-slate-500 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl'"
                                    class="transition-colors flex items-center justify-center"
                                    title="{{ __('Search') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Language Switcher Dropdown -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen" class="h-10 px-3 text-xs font-bold uppercase rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 transition-all flex items-center gap-1.5 shadow-sm">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="langOpen" @click.outside="langOpen = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl py-1 z-50 text-xs">
                        <a href="{{ route('locale.switch', 'en') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'en' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">English</a>
                        <a href="{{ route('locale.switch', 'hi') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'hi' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">हिन्दी (Hindi)</a>
                        <a href="{{ route('locale.switch', 'ar') }}" class="block px-3 py-1.5 hover:bg-amber-50 dark:hover:bg-slate-800 {{ app()->getLocale() === 'ar' ? 'font-bold text-amber-600' : 'text-slate-700 dark:text-slate-300' }}">العربية (Arabic)</a>
                    </div>
                </div>

                <!-- Theme Toggle Switcher (Light / Dark) -->
                <button type="button"
                        x-data="{
                            isDark: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.isDark = !this.isDark;
                                if (this.isDark) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.setItem('color-theme', 'dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.setItem('color-theme', 'light');
                                }
                            }
                        }"
                        @click="toggle()"
                        class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-amber-400 transition-all flex items-center justify-center shadow-sm flex-shrink-0"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                        aria-label="Toggle theme">
                    <!-- Sun Icon (shown when dark) -->
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon Icon (shown when light) -->
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- Shopping Bag / Active Reservations Icon with Number ON the Bag -->
                @if (isset($activeSessionHolds) && $activeSessionHolds->isNotEmpty())
                    <div>
                        <a href="{{ route('reservations.index') }}"
                           class="h-10 px-2.5 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-slate-950/80 hover:bg-amber-100 dark:hover:bg-slate-900 border border-amber-200 dark:border-amber-500/30 rounded-xl shadow-sm transition-all flex items-center justify-center group"
                           title="{{ __('My Active Holds') }} ({{ $activeSessionHolds->count() }})">
                            <div class="relative flex items-center justify-center w-7 h-7">
                                <!-- Luxury Shopping Bag Icon -->
                                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400 group-hover:scale-105 transition-transform" fill="currentColor" fill-opacity="0.2" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <!-- Hold Count Displayed Directly ON the Bag -->
                                <span class="absolute top-[10px] text-[10px] font-black text-amber-700 dark:text-amber-300 font-mono tracking-tight pointer-events-none">
                                    {{ $activeSessionHolds->count() }}
                                </span>
                            </div>
                        </a>
                    </div>
                @endif

                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen" class="h-10 inline-flex items-center gap-2 px-3.5 text-sm font-medium text-slate-800 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-all shadow-sm">
                            <span class="font-semibold">{{ Auth::user()->name }}</span>
                            <span class="text-[11px] px-2 py-0.5 rounded-full font-bold uppercase {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/80 dark:text-purple-300 border border-purple-300 dark:border-purple-700' : (Auth::user()->isApprovedVendor() ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/80 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/80 dark:text-amber-300 border border-amber-300 dark:border-amber-700') }}">
                                {{ Auth::user()->role }}
                            </span>
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl py-1.5 z-50 transition-colors">
                            @if (auth()->user()->isVendor())
                                <a href="{{ route('vendor.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-amber-600 dark:hover:text-amber-400">{{ __('Vendor Profile') }}</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800">{{ __('Log Out') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="h-10 px-3.5 flex items-center text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}" class="h-10 px-4 flex items-center bg-gold-gradient text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-amber-500/20 hover:brightness-110 transition-all">
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center gap-2 sm:hidden">
                <!-- Mobile Theme Switcher Toggle -->
                <button type="button"
                        x-data="{
                            isDark: document.documentElement.classList.contains('dark'),
                            toggle() {
                                this.isDark = !this.isDark;
                                if (this.isDark) {
                                    document.documentElement.classList.add('dark');
                                    localStorage.setItem('color-theme', 'dark');
                                } else {
                                    document.documentElement.classList.remove('dark');
                                    localStorage.setItem('color-theme', 'light');
                                }
                            }
                        }"
                        @click="toggle()"
                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-amber-400 transition-all flex items-center justify-center">
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors">
        <!-- Mobile Search -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-800">
            <form method="GET" action="{{ $searchTargetRoute }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('Search jewellery, SKU, vendor...') }}"
                           class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-amber-500 focus:border-amber-500">
                    <button type="submit" class="absolute right-2.5 top-2 text-slate-400 hover:text-amber-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            @if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isVendor()))
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                    {{ __('Explore Catalogue') }}
                </x-responsive-nav-link>

                @if (isset($activeSessionHolds) && $activeSessionHolds->isNotEmpty())
                    <x-responsive-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')">
                        ⏱ {{ __('My Active Holds') }} ({{ $activeSessionHolds->count() }})
                    </x-responsive-nav-link>
                @endif
            @endif

            @auth
                @if (auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>
                @endif
                @if (auth()->user()->isVendor())
                    <x-responsive-nav-link :href="route('vendor.dashboard')" :active="request()->routeIs('vendor.*')">
                        {{ __('Vendor Portal') }}
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-200 dark:border-slate-800">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 dark:text-rose-400">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            @else
                <div class="p-4 space-y-2 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('login') }}" class="block text-center py-2 text-sm font-semibold text-slate-700 dark:text-slate-300">
                        {{ __('Log in') }}
                    </a>
                    <a href="{{ route('register') }}" class="block text-center py-2 bg-gold-gradient text-slate-950 font-bold text-xs uppercase tracking-wider rounded-xl">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
