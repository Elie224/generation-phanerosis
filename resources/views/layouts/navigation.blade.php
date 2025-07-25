<nav x-data="{ open: false }" class="bg-white border-b border-orange-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('logo.jpeg') }}" alt="Logo" class="block h-9 w-auto rounded-full shadow">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <style>
                        .menu-dropdown { position: relative; display: flex; align-items: center; height: 100%; }
                        .menu-dropdown > button {
                            height: 40px;
                            display: flex;
                            align-items: center;
                            padding-top: 0;
                            padding-bottom: 0;
                            background: none;
                            border: none;
                            font: inherit;
                            cursor: pointer;
                        }
                        .menu-dropdown-content {
                            display: none;
                            position: absolute;
                            left: 0;
                            min-width: 220px;
                            background: #fff;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                            border-radius: 0.5rem;
                            z-index: 99999;
                            margin-top: 0.5rem;
                            right: auto;
                            max-height: 70vh;
                            overflow-y: auto;
                            /* Empêche d'être coupé en bas */
                            bottom: auto;
                        }
                        .menu-dropdown:hover .menu-dropdown-content,
                        .menu-dropdown:focus-within .menu-dropdown-content {
                            display: block;
                        }
                        /* Si le menu est trop bas, l'afficher vers le haut */
                        @media (max-width: 900px) {
                            .menu-dropdown-content {
                                left: auto;
                                right: 0;
                            }
                        }
                        @media (max-height: 500px) {
                            .menu-dropdown-content {
                                max-height: 50vh;
                            }
                        }
                        .menu-dropdown-content a {
                            display: block;
                            padding: 0.5rem 1rem;
                            color: #374151;
                            text-decoration: none;
                            font-size: 0.95em;
                            border-radius: 0.25rem;
                            transition: background 0.2s, color 0.2s;
                        }
                        .menu-dropdown-content a:hover {
                            background: #fef3c7;
                            color: #ea580c;
                        }
                    </style>
                    <div class="menu-dropdown">
                        <a href="#" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            Messagerie
                            <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                        <div class="menu-dropdown-content">
                            <a href="{{ route('messages.index') }}">Messages</a>
                            <a href="{{ route('friends.index') }}">Amis</a>
                            <a href="{{ route('announcements.index') }}">Annonces</a>
                            <a href="{{ route('users.index') }}">Utilisateurs</a>
                        </div>
                    </div>
                    <x-nav-link :href="route('teachings.index')" :active="request()->routeIs('teachings.*')">
                        {{ __('Prédications & Enseignements') }}
                    </x-nav-link>
                    <x-nav-link :href="route('support.index')" :active="request()->routeIs('support.index')">
                        {{ __('Don de Soutien') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Événements') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('resources.index')" :active="request()->routeIs('resources.*')">
                        {{ __('Ressources') }}
                    </x-nav-link>
                    <x-nav-link :href="route('prayer.index')" :active="request()->routeIs('prayer.*')">
                        {{ __('Prières') }}
                    </x-nav-link>
                    <x-nav-link :href="route('opportunites-talents.public')" :active="request()->routeIs('opportunites-talents.public')">
                        {{ __('Opportunités & Talents') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::check() ? Auth::user()->name : 'Invité' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.my')">
                            {{ __('Mon Profil') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Modifier le Profil') }}
                        </x-dropdown-link>

                        @if(auth()->check() && auth()->user()->isAdmin())
                        <x-dropdown-link :href="route('admin.dashboard')">
                            {{ __('Administration') }}
                        </x-dropdown-link>
                        @endif

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
            <x-responsive-nav-link :href="route('teachings.index')" :active="request()->routeIs('teachings.*')">
                {{ __('Prédications & Enseignements') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('support.index')" :active="request()->routeIs('support.index')">
                {{ __('Don de Soutien') }}
            </x-responsive-nav-link>
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        Messagerie
                        <svg class="ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-responsive-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                        {{ __('Messages') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('friends.index')" :active="request()->routeIs('friends.*')">
                        {{ __('Amis') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')">
                        {{ __('Annonces') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Utilisateurs') }}
                    </x-responsive-nav-link>
                </x-slot>
            </x-dropdown>
            <x-responsive-nav-link :href="route('prayer.index')" :active="request()->routeIs('prayer.*')">
                {{ __('Prières') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::check() ? Auth::user()->name : 'Invité' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::check() ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.my')">
                    {{ __('Mon Profil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Modifier le Profil') }}
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
</nav>
