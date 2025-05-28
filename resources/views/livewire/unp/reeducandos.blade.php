@section('title', 'Reeducandos')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ Request::path() }}
    </h2>
</x-slot>

<div>
    @if (session()->has('message'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
            role="alert">
            <div class="flex">
                <div>
                    <p class="text-sm">{{ session('message') }}</p>
                    @if (!empty($results))
                        <p class="text-sm mt-1">Resultados encontrados: {{ $results->total() }}</p>
                    @endif
                </div>
            </div>
        </div>
    @elseif (session()->has('error'))
        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @elseif (!empty($results))
        <p class="text-gray-500 dark:text-gray-400 text-sm my-3">Resultados encontrados: {{ $results->total() }}</p>
    @endif

    @if (!empty($errorMessage))
        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3"
            role="alert">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <button wire:click="create"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Criar novo Reeducando
                </button>

                <div class="my-4">
                    <form wire:submit.prevent="search" class="max-w-lg mx-auto">
                        <div class="flex">
                            <input type="text" wire:model.live="searchTerm"
                                class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Pesquisar por nome do reeducando...">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Pesquisar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modal de Criar/Editar -->
                @if ($isOpen)
                    <div wire:key="create-edit-modal" x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" x-show="open"
                        x-on:keydown.escape.window="open && $wire.closeModal()"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95" @click.self="$wire.closeModal()"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 overflow-y-auto max-h-[90vh]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                {{ $reeducando_id ? 'Editar Reeducando' : 'Criar Reeducando' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Nome -->
                                    <div>
                                        <label for="nome"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                        <input id="nome" type="text" wire:model="nome"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 capitalize"
                                            placeholder="ex: João Silva" required>
                                        <x-input-error for="nome" class="mt-2" />
                                    </div>
                                    <!-- Curso -->
                                    <div>
                                        <label for="curso_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                                        <select id="curso_id" wire:model="curso_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            required>
                                            <option value="">-- Selecione um curso --</option>
                                            @foreach ($cursos as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="curso_id" class="mt-2" />
                                    </div>
                                    <!-- Documento -->
                                    <div>
                                        <label for="documento"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Documento</label>
                                        <input id="documento" type="text" wire:model="documento"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: CPF ou RG" required>
                                        <x-input-error for="documento" class="mt-2" />
                                    </div>
                                    <!-- Carga Horária -->
                                    <div>
                                        <label for="carga"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carga
                                            Horária</label>
                                        <input id="carga" type="text" wire:model="carga"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: 40h" required>
                                        <x-input-error for="carga" class="mt-2" />
                                    </div>
                                    <!-- Observações -->
                                    <div>
                                        <label for="observacoes"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                                        <textarea id="observacoes" wire:model="observacoes"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Observações sobre o reeducando"></textarea>
                                        <x-input-error for="observacoes" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" wire:click="closeModal"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                        {{ $reeducando_id ? 'Atualizar' : 'Criar' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Modal de Visualização -->
                @if ($isViewOpen)
                    <div wire:key="view-modal" x-data="{ open: {{ $isViewOpen ? 'true' : 'false' }} }" x-show="open"
                        x-on:keydown.escape.window="open && $wire.closeViewModal()"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95" @click.self="$wire.closeViewModal()"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        @if ($selectedReeducando)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 overflow-y-auto max-h-[90vh]">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $selectedReeducando->nome }}</h3>
                                    <button wire:click="closeViewModal"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-md font-semibold text-blue-500">Dados do Reeducando</h4>
                                        <div class="border-t-2 border-blue-500 my-2"></div>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Nome:</strong>
                                            {{ $selectedReeducando->nome }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Curso:</strong>
                                            {{ $selectedReeducando->curso->nome ?? 'Não informado' }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Documento:</strong>
                                            {{ $selectedReeducando->documento }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Carga Horária:</strong>
                                            {{ $selectedReeducando->carga }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Observações:</strong>
                                            {{ $selectedReeducando->observacoes ?? 'Não informado' }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><strong>Status do Curso:</strong>
                                            {{ $selectedReeducando->curso->status ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Erro</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-6">Não foi possível carregar os dados do
                                    reeducando.</p>
                                <div class="flex justify-end">
                                    <button wire:click="closeViewModal"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Modal de Confirmação de Exclusão -->
                @if ($confirmDeleteId)
                    <div wire:key="delete-modal" x-data="{ open: {{ $confirmDeleteId ? 'true' : 'false' }} }" x-show="open"
                        x-on:keydown.escape.window="open && $wire.set('confirmDeleteId', null)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        @click.self="$wire.set('confirmDeleteId', null)"
                        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Confirmar Exclusão
                                </h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">Tem certeza que deseja apagar este
                                reeducando? Esta ação não pode ser desfeita.</p>
                            <div class="flex justify-end gap-2">
                                <button wire:click="$set('confirmDeleteId', null)"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                    Cancelar
                                </button>
                                <button wire:click="delete"
                                    class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
                                    </svg>
                                    Apagar
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($results))
                    <!-- Tabela para Desktop -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Nome</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Curso</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $reeducando)
                                    <tr wire:key="table-row-{{ $reeducando->id }}-{{ $index }}"
                                        class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }}">
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $reeducando->nome }}">{{ $reeducando->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $reeducando->curso->nome ?? 'N/A' }}">
                                            {{ $reeducando->curso->nome ?? 'N/A' }}</td>
                                        <td class="border px-4 py-3 flex gap-2">
                                            <button wire:click="view({{ $reeducando->id }})"
                                                class="bg-green-500 hover:bg-green-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Ver reeducando">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                            <button wire:click="edit({{ $reeducando->id }})"
                                                class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Editar reeducando">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $reeducando->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Apagar reeducando">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
                                                </svg>
                                                Apagar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4 flex justify-end">
                            {{ $results->links() }}
                        </div>
                    </div>

                    <!-- Lista de Cartões para Mobile -->
                    <div class="md:hidden space-y-4">
                        @foreach ($results as $index => $reeducando)
                            <div wire:key="card-{{ $reeducando->id }}-{{ $index }}"
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $reeducando->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Curso:
                                            {{ $reeducando->curso->nome ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="view({{ $reeducando->id }})"
                                            class="bg-green-500 hover:bg-green-700 text-white p-2 rounded"
                                            aria-label="Ver reeducando">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $reeducando->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded"
                                            aria-label="Editar reeducando">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $reeducando->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded"
                                            aria-label="Apagar reeducando">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4 flex justify-center">
                            {{ $results->links() }}
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">Nenhum reeducando encontrado. Use o campo
                        acima para pesquisar ou crie um novo.</p>
                @endif
            </div>
        </div>
    </div>
</div>
