<nav x-data="{ open: false, avatarModal: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Avatar -->
                <div class="shrink-0 flex items-center">
                    <button @click="avatarModal = true" class="cursor-pointer focus:outline-none">
                        @if(Auth::user()->avatar)
                            <img 
                                src="{{ strpos(Auth::user()->avatar, 'data:') === 0 ? Auth::user()->avatar : route('profile.avatar', Auth::user()->id) }}" 
                                alt="{{ Auth::user()->name }}" 
                                class="h-9 w-9 rounded-full object-cover border-2 border-gray-300 hover:border-gray-400 transition"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold hover:bg-gray-300 transition" style="display: none;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @else
                            <div class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-bold hover:bg-indigo-600 transition">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('links.index')" :active="request()->routeIs('links.*')">
                        {{ __('Ссылки') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Быстрый доступ к профилю --}}
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-indigo-600 bg-indigo-50 border-l-4 border-indigo-600 hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out">
                👤 В профиль
            </a>
            
            <x-responsive-nav-link :href="route('links.index')" :active="request()->routeIs('links.*')">
                {{ __('Ссылки') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <!-- Avatar Modal -->
    <div 
        x-show="avatarModal" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.self="avatarModal = false"
        @keydown.escape.window="avatarModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display: none;"
    >
        <div 
            x-show="avatarModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative max-w-2xl max-h-[90vh] p-4"
        >
            @if(Auth::user()->avatar)
                <img 
                    src="{{ strpos(Auth::user()->avatar, 'data:') === 0 ? Auth::user()->avatar : route('profile.avatar', Auth::user()->id) }}" 
                    alt="{{ Auth::user()->name }}" 
                    class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain"
                >
            @else
                <div class="w-64 h-64 rounded-full bg-indigo-500 flex items-center justify-center text-white text-6xl font-bold mx-auto">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
        </div>
    </div>
</nav>
