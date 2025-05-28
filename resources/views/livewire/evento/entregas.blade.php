@section('title', 'Distribuição de Cestas')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ Request::path() }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            <!-- Mensagem de Resultados -->
            @if (!empty($terreiros) || !empty($instituicoes))
                <div class="bg-gray-100 dark:bg-gray-700 rounded-b text-gray-900 dark:text-gray-100 px-4 py-3 shadow-md my-3" role="alert">
                    <p class="text-sm">
                        Resultados encontrados: {{ count($terreiros) + count($instituicoes) }}
                        ({{ count($terreiros) }} terreiros, {{ count($instituicoes) }} instituições)
                    </p>
                </div>
            @endif

            <!-- Campo de Pesquisa -->
            <div class="my-4">
                <form wire:submit.prevent="render" class="max-w-lg mx-auto">
                    <div class="flex">
                        <input wire:model.live="search" type="text"
                            placeholder="Pesquisar por nome..."
                            class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Pesquisar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabelas para Desktop -->
            <div class="hidden md:block overflow-x-auto">
                <!-- Seção Terreiros -->
                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">TERREIROS</h2>
                <table class="w-full border-collapse mb-6">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3 text-left">Nome</th>
                            <th class="p-3 text-center">Cestas</th>
                            <th class="p-3 text-center">Convidados</th>
                            <th class="p-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terreiros as $index => $item)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-3 border-t text-gray-700 dark:text-gray-200">{{ $item['nome'] }}</td>
                                <td class="p-3 border-t text-center text-gray-700 dark:text-gray-200">
                                    @if ($item['cestas'])
                                        {{ $item['cestas'] }}
                                    @else
                                        <span class="text-red-500 font-bold">!</span>
                                    @endif
                                </td>
                                <td class="p-3 border-t text-center text-gray-700 dark:text-gray-200">{{ $item['convidados'] }}</td>
                                <td class="p-3 border-t text-center">
                                    <button wire:click="viewDetails({{ $item['id'] }}, '{{ $item['tipo'] }}')"
                                        class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="4" class="p-3 text-center text-gray-500 dark:text-gray-400">Nenhum terreiro encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Seção Instituições -->
                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">INSTITUIÇÕES</h2>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3 text-left">Nome</th>
                            <th class="p-3 text-center">Cestas</th>
                            <th class="p-3 text-center">Convidados</th>
                            <th class="p-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instituicoes as $index => $item)
                            <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }} hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="p-3 border-t text-gray-700 dark:text-gray-200">{{ $item['nome'] }}</td>
                                <td class="p-3 border-t text-center text-gray-700 dark:text-gray-200">
                                    @if ($item['cestas'])
                                        {{ $item['cestas'] }}
                                    @else
                                        <span class="text-red-500 font-bold">!</span>
                                    @endif
                                </td>
                                <td class="p-3 border-t text-center text-gray-700 dark:text-gray-200">{{ $item['convidados'] }}</td>
                                <td class="p-3 border-t text-center">
                                    <button wire:click="viewDetails({{ $item['id'] }}, '{{ $item['tipo'] }}')"
                                        class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="4" class="p-3 text-center text-gray-500 dark:text-gray-400">Nenhuma instituição encontrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Lista de Cartões para Mobile -->
            <div class="md:hidden space-y-6">
                <!-- Terreiros -->
                <div>
                    <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">TERREIROS</h2>
                    @forelse($terreiros as $index => $item)
                        <div wire:key="terreiro-card-{{ $item['id'] }}-{{ $index }}"
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item['nome'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Cestas: 
                                        @if ($item['cestas'])
                                            {{ $item['cestas'] }}
                                        @else
                                            <span class="text-red-500 font-bold">!</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Convidados: {{ $item['convidados'] }}</p>
                                </div>
                                <button wire:click="viewDetails({{ $item['id'] }}, '{{ $item['tipo'] }}')"
                                    class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum terreiro encontrado</p>
                    @endforelse
                </div>

                <!-- Instituições -->
                <div>
                    <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200">INSTITUIÇÕES</h2>
                    @forelse($instituicoes as $index => $item)
                        <div wire:key="instituicao-card-{{ $item['id'] }}-{{ $index }}"
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item['nome'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Cestas: 
                                        @if ($item['cestas'])
                                            {{ $item['cestas'] }}
                                        @else
                                            <span class="text-red-500 font-bold">!</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Convidados: {{ $item['convidados'] }}</p>
                                </div>
                                <button wire:click="viewDetails({{ $item['id'] }}, '{{ $item['tipo'] }}')"
                                    class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma instituição encontrada</p>
                    @endforelse
                </div>
            </div>

            <!-- Modal de Visualização -->
            @if ($selectedEntidade)
                <div wire:key="view-modal" x-data="{ open: {{ $selectedEntidade ? 'true' : 'false' }} }" x-show="open"
                    x-on:keydown.escape.window="open && $wire.closeModal()"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    @click.self="$wire.closeModal()"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 overflow-y-auto max-h-[90vh]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedEntidade['nome'] }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <h4 class="text-md font-semibold text-red-500">Detalhes</h4>
                                <div class="border-t-2 border-red-500 my-2"></div>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Cestas:</strong> {{ $selectedEntidade['cestas'] ?? 'Não recebeu' }}</p>
                                <p class="text-gray-700 dark:text-gray-300"><strong>Observação:</strong> {{ $selectedEntidade['observacao'] ?? 'Nenhuma' }}</p>
                            </div>
                            @if ($selectedEntidade['foto'])
                                <div>
                                    <h4 class="text-md font-semibold text-red-500">Foto</h4>
                                    <div class="border-t-2 border-red-500 my-2"></div>
                                    <img src="{{ asset($selectedEntidade['foto']) }}" alt="Foto"
                                        class="mt-2 w-full max-h-[500px] object-contain rounded mx-auto">
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-end mt-6">
                            <button wire:click="closeModal"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>