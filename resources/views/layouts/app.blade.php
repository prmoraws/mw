<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MW | @yield('title')</title>
    <meta name="description"
        content="Aplicação web desenvolvida por J.M.Moraes, utilizando Laravel, Livewire e Tailwind CSS. Solução eficiente para gestão.">
    <meta name="keywords" content="moraw, laravel, livewire, tailwind, aplicação web, desenvolvimento web, Moraes">
    <meta name="author" content="J.M.Moraes">

    <!-- Open Graph (Redes Sociais) -->
    <meta property="og:title" content="Moraw | Aplicação Web Moderna">
    <meta property="og:description" content="Desenvolvida com Laravel e Livewire para para gestão privada.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://moraw.ct.ws">
    <meta property="og:image" content="https://moraw.ct.ws/uploads/moraw-1600x630-thumbnail.jpg">
    <meta property="og:site_name" content="Moraw">
    <link rel="icon"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 440 376'%3E%3Cpath d='M 56.53 6.41 h 152.93 v 366.88 L 70.37 178.11 h 62.28 l 33.07 45.89 V 69.06 H 74.71 l -9.85 13.81 L 87.53 115.2 l -62.43 0.5 L 2 84 z' style='fill:%2399f'/%3E%3Cpath d='M229.93 6.29h152.83l54.2 76.26-72.48 101.38-0.18-87.61 9.85-13.31-9.67-13.81-23.22-0.25-0.35 148.54-43.63 61.17-1.76-206.77H271.3l1.05 239.85-45.03 61.17z' style='fill:%2399f;fill-opacity:.811765'/%3E%3C/svg%3E"
        sizes="any" type="image/svg+xml">

    <link rel="icon"
        href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 440 376'%3E%3Cpath d='M 56.53 6.41 h 152.93 v 366.88 L 70.37 178.11 h 62.28 l 33.07 45.89 V 69.06 H 74.71 l -9.85 13.81 L 87.53 115.2 l -62.43 0.5 L 2 84 z' style='fill:%2399f'/%3E%3Cpath d='M229.93 6.29h152.83l54.2 76.26-72.48 101.38-0.18-87.61 9.85-13.31-9.67-13.81-23.22-0.25-0.35 148.54-43.63 61.17-1.76-206.77H271.3l1.05 239.85-45.03 61.17z' style='fill:%2399f;fill-opacity:.811765'/%3E%3C/svg%3E"
        sizes="any" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Moraw",
          "url": "https://moraw.ct.ws",
          "description": "Desenvolvida com Laravel e Livewire.",
          "author": {
            "@type": "Person",
            "name": "J.M.Moraes"
          }
        }
        </script>
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
            Desenvolvido com
            <svg class="inline-block size-4" xmlns="http://www.w3.org/2000/svg" fill="#38BDF8" viewBox="0 0 24 24">
                <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg> por J.M.Moraes
            © {{ date('Y') }}
        </div>
    </footer>

    @stack('modals')

    @livewireScripts
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>

</html>
