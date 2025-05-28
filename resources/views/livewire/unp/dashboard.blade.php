@section('title', 'Dashboard UNP')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard UNP
    </h2>
</x-slot>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Dashboard UNP</h1>

    <!-- Botões de Ação -->
    <div class="flex justify-end mb-6 space-x-4">
        <button wire:click="exportData" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
            Exportar Dados
        </button>
        <button wire:loading.attr="disabled" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
            Atualizar Dashboard
        </button>
    </div>

    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Cursos -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">CURSOS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $cursosCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">CURSOS ATIVOS</p>
            <div class="max-h-[150px] overflow-y-auto mt-2">
                <table class="w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-2 py-1 text-left">Nome</th>
                            <th class="px-2 py-1 text-left">Unidade</th>
                            <th class="px-2 py-1 text-left">Fim</th>
                            <th class="px-2 py-1 text-left">Alerta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cursos as $curso)
                            <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }}">
                                <td class="px-2 py-1 truncate max-w-[100px]" title="{{ $curso['nome'] }}">{{ $curso['nome'] }}</td>
                                <td class="px-2 py-1 truncate max-w-[100px]" title="{{ $curso['unidade'] }}">{{ $curso['unidade'] }}</td>
                                <td class="px-2 py-1">{{ $curso['fim'] }}</td>
                                <td class="px-2 py-1">
                                    @if ($curso['alert_color'] === 'red')
                                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif ($curso['alert_color'] === 'green')
                                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('cursos')" class="text-xs">
                    VER CURSOS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Instrutores -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">INSTRUTORES</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $instrutoresCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">INSTRUTORES ATIVOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('instrutores')" class="text-xs">
                    VER INSTRUTORES
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Grupos -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 1.857a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">GRUPOS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $gruposCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">CURSOS DISTINTOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('grupos')" class="text-xs">
                    VER GRUPOS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Formaturas -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">FORMATURAS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $formaturasCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">EVENTOS DE FORMATURA</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('formaturas')" class="text-xs">
                    VER FORMATURAS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Reeducandos -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">REEDUCANDOS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $reeducandosCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">REEDUCANDOS MATRICULADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('reeducandos')" class="text-xs">
                    VER REEDUCANDOS
                </x-secondary-button>
            </div>
        </div>
    </div>

    <!-- Seção de Gráficos -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Barras: Cursos por Unidade -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Cursos por Unidade</h3>
            <canvas id="cursosPorUnidadeChart" class="w-full h-64"></canvas>
        </div>

        <!-- Gráfico de Linha: Formaturas por Mês -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Formaturas por Mês</h3>
            <canvas id="formaturasPorMesChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Script para Gráficos com Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Barras: Cursos por Unidade
        const ctxCursos = document.getElementById('cursosPorUnidadeChart').getContext('2d');
        new Chart(ctxCursos, {
            type: 'bar',
            data: {
                labels: @json($chartCursosPorUnidade['labels']),
                datasets: [{
                    label: 'Cursos',
                    data: @json($chartCursosPorUnidade['data']),
                    backgroundColor: 'rgba(79, 70, 229, 0.5)',
                    borderColor: 'rgba(79, 70, 229, 1)',
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

        // Gráfico de Linha: Formaturas por Mês
        const ctxFormaturas = document.getElementById('formaturasPorMesChart').getContext('2d');
        new Chart(ctxFormaturas, {
            type: 'line',
            data: {
                labels: @json($chartFormaturasPorMes['labels']),
                datasets: [{
                    label: 'Formaturas',
                    data: @json($chartFormaturasPorMes['data']),
                    backgroundColor: 'rgba(236, 72, 153, 0.5)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 2,
                    fill: false
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