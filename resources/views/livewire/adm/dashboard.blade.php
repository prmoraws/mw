@section('title', 'Dashboard ADM')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard ADM
    </h2>
</x-slot>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Dashboard ADM</h1>

    <!-- Botões de Ação e Filtros -->
    <div class="flex flex-col sm:flex-row justify-between ml-1 mb-6 space-y-4 sm:space-y-0 sm:space-x-4">
        <div class="flex space-x-4">
            <button wire:click="exportData" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                Exportar Dados
            </button>
            <button wire:loading.attr="disabled" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
                Atualizar Dashboard
            </button>
        </div>
        <div>
            <label for="sessionDateFilter" class="text-sm text-gray-600 dark:text-gray-400 mr-2">Filtrar Sessões:</label>
            <select wire:model="sessionDateFilter" id="sessionDateFilter" class="border-gray-300 dark:border-gray-600 rounded-lg text-sm px-2 py-1 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                <option value="7_days">Últimos 7 Dias</option>
                <option value="30_days">Últimos 30 Dias</option>
                <option value="all">Todas</option>
            </select>
        </div>
    </div>

    <!-- Grid de Cards (Relatórios de Cabeçalho) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Usuários -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">USUÁRIOS</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $usersCount }}</p>
            <div class="max-h-[150px] overflow-y-auto">
                <table class="w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-2 py-1 text-left">Avatar</th>
                            <th class="px-2 py-1 text-left">Nome</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }}">
                                <td class="px-2 py-1">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-xs font-semibold">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1 truncate max-w-[200px]" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card Sistema (Cache, Jobs, Sessions) -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">SISTEMA</h3>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $cachesCount }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">CACHES</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $jobsCount }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">JOBS</p>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $sessionsCount }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">SESSÕES</p>
                </div>
            </div>
            <div class="max-h-[150px] overflow-y-auto">
                <table class="w-full text-sm text-gray-900 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-2 py-1 text-left">IP Address</th>
                            <th class="px-2 py-1 text-left">User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sessionsDetails as $session)
                            <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800' }}">
                                <td class="px-2 py-1 truncate max-w-[150px]" title="{{ $session->ip_address }}">
                                    {{ $session->ip_address }}
                                </td>
                                <td class="px-2 py-1 truncate max-w-[200px]" title="{{ $session->user_agent }}">
                                    {{ $session->user_agent }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Seção de Gráficos -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Bar Chart: Usuários por Período -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Usuários por Período (Mês/Ano)</h3>
            <canvas id="usersPorPeriodoChart" class="w-full h-64"></canvas>
        </div>

        <!-- Bar Chart: Jobs por Status -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Jobs por Status</h3>
            <canvas id="jobsPorStatusChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Script para Gráficos com Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart: Usuários por Período
        const ctxUsers = document.getElementById('usersPorPeriodoChart').getContext('2d');
        new Chart(ctxUsers, {
            type: 'bar',
            data: {
                labels: @json($chartUsersPorPeriodo['labels']),
                datasets: [{
                    label: 'Usuários',
                    data: @json($chartUsersPorPeriodo['data']),
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

        // Bar Chart: Jobs por Status
        const ctxJobs = document.getElementById('jobsPorStatusChart').getContext('2d');
        new Chart(ctxJobs, {
            type: 'bar',
            data: {
                labels: @json($chartJobsPorStatus['labels']),
                datasets: [{
                    label: 'Jobs',
                    data: @json($chartJobsPorStatus['data']),
                    backgroundColor: [
                        'rgba(236, 72, 153, 0.5)', // Pendente
                        'rgba(16, 185, 129, 0.5)', // Processado
                        'rgba(239, 68, 68, 0.5)'   // Falhado
                    ],
                    borderColor: [
                        'rgba(236, 72, 153, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
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