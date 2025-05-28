<nav x-data="{ open: false, admOpen: false, eventosOpen: false, universalOpen: false, unpOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex w-full">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex rounded-lg p-2 flex-1 justify-center">
                    <!-- ADM Dropdown -->
                    @if (auth()->user()->hasAnyPermission([
                                'view adm dashboard',
                                'view unp dashboard',
                                'view evento dashboard',
                                'view universal dashboard',
                            ]))
                        <div class="relative m-2 pr-6 pl-6 pt-2" x-data="{ open: false }" @click.away="open = false">
                            <x-nav-link href="#" @click.prevent="open = !open"
                                class="rounded-lg text-center flex flex-row items-center gap-1">
                                {{ __('ADM') }}
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </x-nav-link>
                            <div x-show="open"
                                class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                @if (auth()->user()->hasPermissionTo('view unp dashboard'))
                                    <x-dropdown-link href="{{ route('dashboard.unp') }}" :active="request()->routeIs('dashboard.unp')"
                                        class="hover:bg-blue-100">{{ __('UNP') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view evento dashboard'))
                                    <x-dropdown-link href="{{ route('dashboard.ev') }}" :active="request()->routeIs('dashboard.ev')"
                                        class="hover:bg-blue-100">{{ __('Eventos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view universal dashboard'))
                                    <x-dropdown-link href="{{ route('dashboard.uni') }}" :active="request()->routeIs('dashboard.uni')"
                                        class="hover:bg-blue-100">{{ __('Universal') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view adm dashboard'))
                                    <x-dropdown-link href="{{ route('dashboard.adm') }}" :active="request()->routeIs('dashboard.adm')"
                                        class="hover:bg-blue-100">{{ __('ADM') }}</x-dropdown-link>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Eventos Dropdown -->
                    @if (auth()->user()->hasAnyPermission(['view terreiros', 'view instituicoes', 'view cestas', 'view entregas']))
                        <div class="relative m-2 pr-6 pl-6 pt-2" x-data="{ open: false }" @click.away="open = false">
                            <x-nav-link href="#" @click.prevent="open = !open"
                                class="rounded-lg text-center flex flex-row items-center gap-1">
                                {{ __('Eventos') }}
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </x-nav-link>
                            <div x-show="open"
                                class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                @if (auth()->user()->hasPermissionTo('view terreiros'))
                                    <x-dropdown-link href="{{ route('terreiros') }}" :active="request()->routeIs('terreiros')"
                                        class="hover:bg-blue-100">{{ __('Terreiros') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view instituicoes'))
                                    <x-dropdown-link href="{{ route('instituicoes') }}" :active="request()->routeIs('instituicoes')"
                                        class="hover:bg-blue-100">{{ __('Instituições') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view cestas'))
                                    <x-dropdown-link href="{{ route('cestas') }}" :active="request()->routeIs('cestas')"
                                        class="hover:bg-blue-100">{{ __('Cestas') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view entregas'))
                                    <x-dropdown-link href="{{ route('entregas') }}" :active="request()->routeIs('entregas')"
                                        class="hover:bg-blue-100">{{ __('Distribuição') }}</x-dropdown-link>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Universal Dropdown -->
                    @if (auth()->user()->hasAnyPermission([
                                'view categorias',
                                'view blocos',
                                'view regiaos',
                                'view igrejas',
                                'view pessoas',
                                'view pastores',
                                'view banners',
                            ]))
                        <div class="relative m-2 pr-6 pl-6 pt-2" x-data="{ open: false }" @click.away="open = false">
                            <x-nav-link href="#" @click.prevent="open = !open"
                                class="rounded-lg text-center flex flex-row items-center gap-1">
                                {{ __('Universal') }}
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </x-nav-link>
                            <div x-show="open"
                                class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                @if (auth()->user()->hasPermissionTo('view categorias'))
                                    <x-dropdown-link href="{{ route('categorias') }}" :active="request()->routeIs('categorias')"
                                        class="hover:bg-blue-100">{{ __('Categorias') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view igrejas'))
                                    <x-dropdown-link href="{{ route('igrejas') }}" :active="request()->routeIs('igrejas')"
                                        class="hover:bg-blue-100">{{ __('Igrejas') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view regiaos'))
                                    <x-dropdown-link href="{{ route('regiaos') }}" :active="request()->routeIs('regiaos')"
                                        class="hover:bg-blue-100">{{ __('Regiões') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view blocos'))
                                    <x-dropdown-link href="{{ route('blocos') }}" :active="request()->routeIs('blocos')"
                                        class="hover:bg-blue-100">{{ __('Blocos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view pastores'))
                                    <x-dropdown-link href="{{ route('pastores') }}" :active="request()->routeIs('pastores')"
                                        class="hover:bg-blue-100">{{ __('Pastores') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view pessoas'))
                                    <x-dropdown-link href="{{ route('pessoas') }}" :active="request()->routeIs('pessoas')"
                                        class="hover:bg-blue-100">{{ __('Pessoas') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view banners'))
                                    <x-dropdown-link href="{{ route('banners') }}" :active="request()->routeIs('banners')"
                                        class="hover:bg-blue-100">{{ __('Banners') }}</x-dropdown-link>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- UNP Dropdown -->
                    @if (auth()->user()->hasAnyPermission([
                                'view cursos',
                                'view formaturas',
                                'view instrutores',
                                'view reeducandos',
                                'view cargos',
                                'view grupos',
                                'view presidios',
                            ]))
                        <div class="relative m-2 pr-6 pl-6 pt-2" x-data="{ open: false }" @click.away="open = false">
                            <x-nav-link href="#" @click.prevent="open = !open"
                                class="rounded-lg text-center flex flex-row items-center gap-1">
                                {{ __('UNP') }}
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </x-nav-link>
                            <div x-show="open"
                                class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                @if (auth()->user()->hasPermissionTo('view cursos'))
                                    <x-dropdown-link href="{{ route('cursos') }}" :active="request()->routeIs('cursos')"
                                        class="hover:bg-blue-100">{{ __('Cursos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view formaturas'))
                                    <x-dropdown-link href="{{ route('formaturas') }}" :active="request()->routeIs('formaturas')"
                                        class="hover:bg-blue-100">{{ __('Formaturas') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view instrutores'))
                                    <x-dropdown-link href="{{ route('instrutores') }}" :active="request()->routeIs('instrutores')"
                                        class="hover:bg-blue-100">{{ __('Instrutores') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view reeducandos'))
                                    <x-dropdown-link href="{{ route('reeducandos') }}" :active="request()->routeIs('reeducandos')"
                                        class="hover:bg-blue-100">{{ __('Reeducandos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view cargos'))
                                    <x-dropdown-link href="{{ route('cargos') }}" :active="request()->routeIs('cargos')"
                                        class="hover:bg-blue-100">{{ __('Cargos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view grupos'))
                                    <x-dropdown-link href="{{ route('grupos') }}" :active="request()->routeIs('grupos')"
                                        class="hover:bg-blue-100">{{ __('Grupos') }}</x-dropdown-link>
                                @endif
                                @if (auth()->user()->hasPermissionTo('view presidios'))
                                    <x-dropdown-link href="{{ route('presidios') }}" :active="request()->routeIs('presidios')"
                                        class="hover:bg-blue-100">{{ __('Presidios') }}</x-dropdown-link>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6" x-data="theme">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme"
                    class="inline-flex items-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 transition ease-in-out duration-150">
                    <svg x-show="!isDarkMode" class="size-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg x-show="isDarkMode" class="size-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 hover:bg-blue-100">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                        class="hover:bg-blue-100">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}" class="hover:bg-blue-100">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" class="hover:bg-blue-100" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition hover:bg-blue-100">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}"
                                        alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150 hover:bg-blue-100">
                                        {{ Auth::user()->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}" class="hover:bg-blue-100">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (auth()->user()->hasPermissionTo('superadmin'))
                                <x-dropdown-link href="{{ route('user-levels') }}" :active="request()->routeIs('user-levels')"
                                    class="hover:bg-blue-100">
                                    {{ __('Níveis') }}
                                </x-dropdown-link>
                            @endif

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}" class="hover:bg-blue-100">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                    class="hover:bg-blue-100">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-blue-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 border rounded-lg p-2 mx-4">
            <!-- ADM Responsive Dropdown -->
            @if (auth()->user()->hasAnyPermission([
                        'view adm dashboard',
                        'view unp dashboard',
                        'view evento dashboard',
                        'view universal dashboard',
                    ]))
                <div x-data="{ open: false }">
                    <x-responsive-nav-link href="#" @click.prevent="open = !open"
                        class="hover:bg-blue-100 rounded-lg flex flex-row items-center gap-1">
                        {{ __('ADM') }}
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </x-responsive-nav-link>
                    <div x-show="open" class="pl-4 space-y-1">
                        @if (auth()->user()->hasPermissionTo('view unp dashboard'))
                            <x-responsive-nav-link href="{{ route('dashboard.unp') }}" :active="request()->routeIs('dashboard.unp')"
                                class="hover:bg-blue-100">{{ __('UNP') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view evento dashboard'))
                            <x-responsive-nav-link href="{{ route('dashboard.ev') }}" :active="request()->routeIs('dashboard.ev')"
                                class="hover:bg-blue-100">{{ __('Eventos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view universal dashboard'))
                            <x-responsive-nav-link href="{{ route('dashboard.uni') }}" :active="request()->routeIs('dashboard.uni')"
                                class="hover:bg-blue-100">{{ __('Universal') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view adm dashboard'))
                            <x-responsive-nav-link href="{{ route('dashboard.adm') }}" :active="request()->routeIs('dashboard.adm')"
                                class="hover:bg-blue-100">{{ __('ADM') }}</x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Eventos Responsive Dropdown -->
            @if (auth()->user()->hasAnyPermission(['view terreiros', 'view instituicoes', 'view cestas', 'view entregas']))
                <div x-data="{ open: false }">
                    <x-responsive-nav-link href="#" @click.prevent="open = !open"
                        class="hover:bg-blue-100 rounded-lg flex flex-row items-center gap-1">
                        {{ __('Eventos') }}
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </x-responsive-nav-link>
                    <div x-show="open" class="pl-4 space-y-1">
                        @if (auth()->user()->hasPermissionTo('view terreiros'))
                            <x-responsive-nav-link href="{{ route('terreiros') }}" :active="request()->routeIs('terreiros')"
                                class="hover:bg-blue-100">{{ __('Terreiros') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view instituicoes'))
                            <x-responsive-nav-link href="{{ route('instituicoes') }}" :active="request()->routeIs('instituicoes')"
                                class="hover:bg-blue-100">{{ __('Instituições') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view cestas'))
                            <x-responsive-nav-link href="{{ route('cestas') }}" :active="request()->routeIs('cestas')"
                                class="hover:bg-blue-100">{{ __('Cestas') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view entregas'))
                            <x-responsive-nav-link href="{{ route('entregas') }}" :active="request()->routeIs('entregas')"
                                class="hover:bg-blue-100">{{ __('Distribuição') }}</x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Universal Responsive Dropdown -->
            @if (auth()->user()->hasAnyPermission([
                        'view categorias',
                        'view blocos',
                        'view regiaos',
                        'view igrejas',
                        'view pessoas',
                        'view pastores',
                        'view banners',
                    ]))
                <div x-data="{ open: false }">
                    <x-responsive-nav-link href="#" @click.prevent="open = !open"
                        class="hover:bg-blue-100 rounded-lg flex flex-row items-center gap-1">
                        {{ __('Universal') }}
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </x-responsive-nav-link>
                    <div x-show="open" class="pl-4 space-y-1">
                        @if (auth()->user()->hasPermissionTo('view categorias'))
                            <x-responsive-nav-link href="{{ route('categorias') }}" :active="request()->routeIs('categorias')"
                                class="hover:bg-blue-100">{{ __('Categorias') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view igrejas'))
                            <x-responsive-nav-link href="{{ route('igrejas') }}" :active="request()->routeIs('igrejas')"
                                class="hover:bg-blue-100">{{ __('Igrejas') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view regiaos'))
                            <x-responsive-nav-link href="{{ route('regiaos') }}" :active="request()->routeIs('regiaos')"
                                class="hover:bg-blue-100">{{ __('Regiões') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view blocos'))
                            <x-responsive-nav-link href="{{ route('blocos') }}" :active="request()->routeIs('blocos')"
                                class="hover:bg-blue-100">{{ __('Blocos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view pastores'))
                            <x-responsive-nav-link href="{{ route('pastores') }}" :active="request()->routeIs('pastores')"
                                class="hover:bg-blue-100">{{ __('Pastores') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view pessoas'))
                            <x-responsive-nav-link href="{{ route('pessoas') }}" :active="request()->routeIs('pessoas')"
                                class="hover:bg-blue-100">{{ __('Pessoas') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view banners'))
                            <x-responsive-nav-link href="{{ route('banners') }}" :active="request()->routeIs('banners')"
                                class="hover:bg-blue-100">{{ __('Banners') }}</x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endif

            <!-- UNP Responsive Dropdown -->
            @if (auth()->user()->hasAnyPermission([
                        'view cursos',
                        'view formaturas',
                        'view instrutores',
                        'view reeducandos',
                        'view cargos',
                        'view grupos',
                        'view presidios',
                    ]))
                <div x-data="{ open: false }">
                    <x-responsive-nav-link href="#" @click.prevent="open = !open"
                        class="hover:bg-blue-100 rounded-lg flex flex-row items-center gap-1">
                        {{ __('UNP') }}
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </x-responsive-nav-link>
                    <div x-show="open" class="pl-4 space-y-1">
                        @if (auth()->user()->hasPermissionTo('view cursos'))
                            <x-responsive-nav-link href="{{ route('cursos') }}" :active="request()->routeIs('cursos')"
                                class="hover:bg-blue-100">{{ __('Cursos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view formaturas'))
                            <x-responsive-nav-link href="{{ route('formaturas') }}" :active="request()->routeIs('formaturas')"
                                class="hover:bg-blue-100">{{ __('Formaturas') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view instrutores'))
                            <x-responsive-nav-link href="{{ route('instrutores') }}" :active="request()->routeIs('instrutores')"
                                class="hover:bg-blue-100">{{ __('Instrutores') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view reeducandos'))
                            <x-responsive-nav-link href="{{ route('reeducandos') }}" :active="request()->routeIs('reeducandos')"
                                class="hover:bg-blue-100">{{ __('Reeducandos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view cargos'))
                            <x-responsive-nav-link href="{{ route('cargos') }}" :active="request()->routeIs('cargos')"
                                class="hover:bg-blue-100">{{ __('Cargos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view grupos'))
                            <x-responsive-nav-link href="{{ route('grupos') }}" :active="request()->routeIs('grupos')"
                                class="hover:bg-blue-100">{{ __('Grupos') }}</x-responsive-nav-link>
                        @endif
                        @if (auth()->user()->hasPermissionTo('view presidios'))
                            <x-responsive-nav-link href="{{ route('presidios') }}" :active="request()->routeIs('presidios')"
                                class="hover:bg-blue-100">{{ __('Presidios') }}</x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600" x-data="theme">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-100 rounded-md">
                    <span x-show="!isDarkMode">{{ __('Modo Claro') }}</span>
                    <span x-show="isDarkMode">{{ __('Modo Escuro') }}</span>
                    <svg x-show="!isDarkMode" class="ms-2 size-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <svg x-show="isDarkMode" class="ms-2 size-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')"
                    class="hover:bg-blue-100">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (auth()->user()->hasPermissionTo('superadmin'))
                    <x-responsive-nav-link href="{{ route('user-levels') }}" :active="request()->routeIs('user-levels')"
                        class="hover:bg-blue-100">
                        {{ __('Níveis') }}
                    </x-responsive-nav-link>
                @endif

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')"
                        class="hover:bg-blue-100">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();"
                        class="hover:bg-blue-100">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                @can('superadmin')
                    <!-- Team Management -->
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Team') }}
                        </div>

                        <!-- Team Settings -->
                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                            :active="request()->routeIs('teams.show')" class="hover:bg-blue-100">
                            {{ __('Team Settings') }}
                        </x-responsive-nav-link>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')"
                                class="hover:bg-blue-100">
                                {{ __('Create New Team') }}
                            </x-responsive-nav-link>
                        @endcan

                        <!-- Team Switcher -->
                        @if (Auth::user()->allTeams()->count() > 1)
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Switch Teams') }}
                            </div>

                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" component="responsive-nav-link"
                                    class="hover:bg-blue-100" />
                            @endforeach
                        @endif
                    @endif
                @endcan
            </div>
        </div>
    </div>
</nav>
