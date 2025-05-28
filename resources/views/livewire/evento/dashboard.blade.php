@section('title', 'Eventos')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Dashboard de Eventos</h1>

    <!-- Botões de Ação -->
    <div class="flex justify-end mb-6 space-x-4">
        <button wire:click="exportData"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition"
            id="export-data-button">
            Exportar Dados
        </button>
        <button wire:loading.attr="disabled"
            class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">
            Atualizar Dashboard
        </button>
    </div>

    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Driblando a Fome -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 1.857a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">DRIBLANDO A FOME</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $terreirosCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">TERREIROS</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $convidadosCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">CONVIDADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('terreiros')" class="text-xs">
                    VER TERREIROS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Instituições -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">INSTITUIÇÕES</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $instituicoesCount }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">INSTITUIÇÕES</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $instituicoesConvidados }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">CONVIDADOS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('lista-instituicao')" class="text-xs">
                    VER INSTITUIÇÕES
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Blocos Driblando a Fome -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">BLOCOS DRIBLANDO A FOME</h3>
            </div>
            <div class="max-h-[100px] overflow-y-auto">
                @foreach ($blocosConvidados as $bloco => $total)
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        <span class="font-semibold">{{ $bloco }}</span>: {{ $total }} CONVIDADOS
                    </p>
                @endforeach
            </div>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('terreiros')" class="text-xs">
                    VER TERREIROS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Terreiros por Bloco -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">TERREIROS POR BLOCO</h3>
            </div>
            <div class="max-h-[100px] overflow-y-auto">
                @foreach ($blocosTerreirosCount as $bloco => $total)
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        <span class="font-semibold">{{ $bloco }}</span>: {{ $total }} TERREIROS
                    </p>
                @endforeach
            </div>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('terreiros')" class="text-xs">
                    VER TERREIROS
                </x-secondary-button>
            </div>
        </div>

        <!-- Card Consolidado 5 de Abril -->
        <div
            class="flex flex-col rounded-lg bg-white dark:bg-gray-800 shadow-sm max-w-96 p-6 border border-gray-200 dark:border-gray-700">
            <div class="pb-4 mb-4 text-center border-b border-gray-200 dark:border-gray-600">
                <p class="text-base uppercase font-bold text-gray-700 dark:text-gray-300">
                    CONSOLIDADO 5 DE ABRIL
                </p>
            </div>
            <div class="p-0">
                <ul class="flex flex-col gap-3">
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total de Terreiros:</span> <span
                                class="font-bold text-base">{{ $terreirosCount }}</span>
                        </p>
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor"
                                class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total Conv. (Terreiros):</span> <span
                                class="font-bold text-base">{{ $convidadosCount }}</span>
                        </p>
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor"
                                class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total de Instituições:</span> <span
                                class="font-bold text-base">{{ $instituicoesCount }}</span>
                        </p>
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor"
                                class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total Conv. (Instituições):</span> <span
                                class="font-bold text-base">{{ $instituicoesConvidados }}</span>
                        </p>
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor"
                                class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total Geral T.+I.:</span> <span
                                class="font-bold text-lg">{{ $totalGeralTerreirosInstituicoes }}</span>
                        </p>
                    </li>
                    <li class="flex items-center gap-3">
                        <span
                            class="p-1 border rounded-full border-gray-300 dark:border-gray-500 bg-gray-100 dark:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor"
                                class="w-4 h-4 text-blue-500 dark:text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                            </svg>
                        </span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            <span class="font-semibold">Total Convidados:</span> <span
                                class="font-bold text-lg">{{ $totalConvidadosGeral }}</span>
                        </p>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Card Cestas Entregues -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-2">
                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h18l-2 13H5L3 3zm4 18a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">CESTAS ENTREGUES</h3>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCestas }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">TOTAL DE CESTAS</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $totalCestasTerreiros }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">TERREIROS ATENDIDOS</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $totalCestasInstituicoes }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">INSTITUIÇÕES ATENDIDAS</p>
            <div class="mt-4">
                <x-secondary-button wire:click="redirectTo('cestas')" class="text-xs">
                    VER CESTAS
                </x-secondary-button>
            </div>
        </div>
    </div>

    <!-- Seção de Gráficos -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Barras: Convidados por Bloco -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Convidados por Bloco</h3>
            <canvas id="blocosConvidadosChart" class="w-full h-64"></canvas>
        </div>

        <!-- Gráfico de Pizza: Distribuição de Cestas -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Distribuição de Cestas</h3>
            <canvas id="cestasDistribuicaoChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Script para Gráficos com Chart.js -->
    @push('scripts')
        <script>
            console.log('Script carregado');

            // Gráfico de Barras: Convidados por Bloco
            const ctxBlocos = document.getElementById('blocosConvidadosChart').getContext('2d');
            new Chart(ctxBlocos, {
                type: 'bar',
                data: {
                    labels: @json($chartBlocosConvidados['labels']),
                    datasets: [{
                        label: 'Convidados',
                        data: @json($chartBlocosConvidados['data']),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
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

            // Gráfico de Pizza: Distribuição de Cestas
            const ctxCestas = document.getElementById('cestasDistribuicaoChart').getContext('2d');
            new Chart(ctxCestas, {
                type: 'pie',
                data: {
                    labels: @json($chartCestasDistribuicao['labels']),
                    datasets: [{
                        data: @json($chartCestasDistribuicao['data']),
                        backgroundColor: ['rgba(59, 130, 246, 0.5)', 'rgba(239, 68, 68, 0.5)'],
                        borderColor: ['rgba(59, 130, 246, 1)', 'rgba(239, 68, 68, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Ouvir evento Livewire para o resultado do exportData
            document.addEventListener('livewire:initialized', () => {
                console.log('Livewire inicializado');
                Livewire.on('exportDataCompleted', (response) => {
                    console.log('Evento exportDataCompleted:', response);
                    if (response && response.url) {
                        window.open(response.url, '_blank');
                    } else {
                        alert('Erro ao gerar o PDF');
                        console.error('Resposta do evento:', response);
                    }
                });
            });
        </script>
    @endpush
</div>
