@section('title', 'Dashboard Universal')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard Universal
    </h2>
</x-slot>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Dashboard Universal</h1>

    <!-- Botões de Ação -->
    <div class="flex justify-end mb-6 space-x-4">
        <button wire:click="exportData" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            Exportar Dados
        </button>
        <button wire:loading.attr="disabled" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
            Atualizar Dashboard
        </button>
    </div>

    <!-- Grid de Cards (Header Reports) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Pessoas -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">PESSOAS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $pessoasCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">PESSOAS CADASTRADAS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('pessoas')" class="text-xs">
                    VER PESSOAS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Igrejas -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">IGREJAS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $igrejasCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">IGREJAS CADASTRADAS</p>
            <div class="max-h-[100px] overflow-y-auto mt-2">
                @foreach ($igrejasPorRegiao as $item)
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        <span class="font-semibold">{{ $item['regiao'] }}</span>: {{ $item['total_igrejas'] }} IGREJAS
                    </p>
                @endforeach
            </div>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('igrejas')" class="text-xs">
                    VER IGREJAS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Pastores -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">PASTORES</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $pastoresCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">PASTORES CADASTRADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('pastores')" class="text-xs">
                    VER PASTORES
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Regiões -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">REGIÕES</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $regioesCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">REGIÕES CADASTRADAS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('regioes')" class="text-xs">
                    VER REGIÕES
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Blocos -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 1.857a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">BLOCOS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $blocosCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">BLOCOS CADASTRADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('blocos')" class="text-xs">
                    VER BLOCOS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Banners -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">BANNERS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $bannersCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">BANNERS CADASTRADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('banners')" class="text-xs">
                    VER BANNERS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Categorias -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">CATEGORIAS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $categoriasCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">CATEGORIAS CADASTRADAS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('categorias')" class="text-xs">
                    VER CATEGORIAS
                </x-secondary-button>
            </div>
        </div>
    </div>

    <!-- Seção de Gráficos -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bullet Chart: Pessoas por Região -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Pessoas por Região</h3>
            <canvas id="pessoasPorRegiaoChart" class="w-full h-64"></canvas>
        </div>

        <!-- Bar Chart: Banners por Período -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Banners por Período (Mês/Ano)</h3>
            <canvas id="bannersPorPeriodoChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Script para Gráficos com Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bullet Chart: Pessoas por Região
        const ctxPessoas = document.getElementById('pessoasPorRegiaoChart').getContext('2d');
        new Chart(ctxPessoas, {
            type: 'bar', // Usando bar como base para simular bullet chart
            data: {
                labels: @json(array_column($chartPessoasPorRegiao, 'title')),
                datasets: [
                    {
                        label: 'Pessoas',
                        data: @json(array_column($chartPessoasPorRegiao, 'measures')),
                        backgroundColor: 'rgba(79, 70, 229, 0.5)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Meta',
                        data: @json(array_column($chartPessoasPorRegiao, 'markers')),
                        type: 'line',
                        borderColor: 'rgba(236, 72, 153, 1)',
                        borderWidth: 2,
                        pointRadius: 5,
                        fill: false
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // Bar Chart: Banners por Período
        const ctxBanners = document.getElementById('bannersPorPeriodoChart').getContext('2d');
        new Chart(ctxBanners, {
            type: 'bar',
            data: {
                labels: @json($chartBannersPorPeriodo['labels']),
                datasets: [{
                    label: 'Banners',
                    data: @json($chartBannersPorPeriodo['data']),
                    backgroundColor: 'rgba(236, 72, 153, 0.5)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</div>