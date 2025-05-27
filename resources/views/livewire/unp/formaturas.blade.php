@section('title', 'Formaturas')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ Request::path() }}
    </h2>
</x-slot>

<div>
    @if (session()->has('message'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
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
        <div class="bg-gray-100 dark:bg-gray-700 rounded-b text-gray-900 dark:text-gray-100 px-4 py-3 shadow-md my-3" role="alert">
            <p class="text-sm">Resultados encontrados: {{ $results->total() }}</p>
        </div>
    @endif

    @if (!empty($errorMessage))
        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Criar nova Formatura
                </button>

                <div class="my-4">
                    <form wire:submit.prevent="search" class="max-w-lg mx-auto">
                        <div class="flex">
                            <input type="text" wire:model.live="searchTerm" class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Pesquisar por presídio ou curso...">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Pesquisar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Modal de Criar/Editar -->
                @if ($isOpen)
                    <div wire:key="create-edit-modal" x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" x-show="open" x-on:keydown.escape.window="open && $wire.closeModal()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" @click.self="$wire.closeModal()" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 overflow-y-auto max-h-[90vh]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                {{ $formatura_id ? 'Editar Formatura' : 'Criar Formatura' }}
                            </h3>
                            <form wire:submit.prevent="store" enctype="multipart/form-data">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <!-- Presídio -->
                                    <div>
                                        <label for="presidio_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Presídio</label>
                                        <select id="presidio_id" wire:model="presidio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                            <option value="">Selecione um presídio</option>
                                            @foreach ($presidios as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="presidio_id" class="mt-2" />
                                    </div>
                                    <!-- Curso -->
                                    <div>
                                        <label for="curso_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Curso</label>
                                        <select id="curso_id" wire:model="curso_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                            <option value="">Selecione um curso</option>
                                            @foreach ($cursos as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="curso_id" class="mt-2" />
                                    </div>
                                    <!-- Instrutor -->
                                    <div>
                                        <label for="instrutor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instrutor</label>
                                        <select id="instrutor_id" wire:model="instrutor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                            <option value="">Selecione um instrutor</option>
                                            @foreach ($instrutores as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="instrutor_id" class="mt-2" />
                                    </div>
                                    <!-- Início -->
                                    <div>
                                        <label for="inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Início</label>
                                        <input id="inicio" type="date" wire:model="inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                        <x-input-error for="inicio" class="mt-2" />
                                    </div>
                                    <!-- Fim -->
                                    <div>
                                        <label for="fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fim</label>
                                        <input id="fim" type="date" wire:model="fim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                                        <x-input-error for="fim" class="mt-2" />
                                    </div>
                                    <!-- Formatura -->
                                    <div>
                                        <label for="formatura" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data da Formatura</label>
                                        <input id="formatura" type="date" wire:model="formatura" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <x-input-error for="formatura" class="mt-2" />
                                    </div>
                                    <!-- Lista -->
                                    <div>
                                        <label for="lista" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lista (PDF)</label>
                                        <input id="lista" type="file" wire:model="lista" accept=".pdf" class="mt-1 block w-full text-gray-700 dark:text-gray-200">
                                        @if ($formatura_id && $selectedFormatura && $selectedFormatura->lista)
                                            <div class="mt-2">
                                                <a href="{{ Storage::disk('public_uploads')->url($selectedFormatura->lista) }}" target="_blank" class="text-blue-500 hover:underline">Arquivo atual: {{ basename($selectedFormatura->lista) }}</a>
                                            </div>
                                        @endif
                                        <x-input-error for="lista" class="mt-2" />
                                    </div>
                                    <!-- Conteúdo -->
                                    <div>
                                        <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo (PDF)</label>
                                        <input id="conteudo" type="file" wire:model="conteudo" accept=".pdf" class="mt-1 block w-full text-gray-700 dark:text-gray-200">
                                        @if ($formatura_id && $selectedFormatura && $selectedFormatura->conteudo)
                                            <div class="mt-2">
                                                <a href="{{ Storage::disk('public_Uploads')->url($selectedFormatura->conteudo) }}" target="_blank" class="text-blue-500 hover:underline">Arquivo atual: {{ basename($selectedFormatura->conteudo) }}</a>
                                            </div>
                                        @endif
                                        <x-input-error for="conteudo" class="mt-2" />
                                    </div>
                                    <!-- Ofício -->
                                    <div>
                                        <label for="oficio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ofício (PDF)</label>
                                        <input id="oficio" type="file" wire:model="oficio" accept=".pdf" class="mt-1 block w-full text-gray-700 dark:text-gray-200">
                                        @if ($formatura_id && $selectedFormatura && $selectedFormatura->oficio)
                                            <div class="mt-2">
                                                <a href="{{ Storage::disk('public_Uploads')->url($selectedFormatura->oficio) }}" target="_blank" class="text-blue-500 hover:underline">Arquivo atual: {{ basename($selectedFormatura->oficio) }}</a>
                                            </div>
                                        @endif
                                        <x-input-error for="oficio" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" wire:click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                        {{ $formatura_id ? 'Atualizar' : 'Criar' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Modal de Visualização -->
                @if ($isViewOpen)
                    <div wire:key="view-modal" x-data="{ open: {{ $isViewOpen ? 'true' : 'false' }} }" x-show="open" x-on:keydown.escape.window="open && $wire.closeViewModal()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" @click.self="$wire.closeViewModal()" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        @if ($selectedFormatura)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6 overflow-y-auto max-h-[90vh]">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $selectedFormatura->presidio->nome }}</h3>
                                    <button wire:click="closeViewModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mb-6">
                                    <h4 class="text-md font-semibold text-blue-500">Detalhes da Formatura</h4>
                                    <div class="border-t-2 border-blue-500 my-2"></div>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Presídio:</strong> {{ $selectedFormatura->presidio->nome }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Curso:</strong> {{ $selectedFormatura->curso->nome }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Instrutor:</strong> {{ $selectedFormatura->instrutor->nome }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Início:</strong> {{ \Carbon\Carbon::parse($selectedFormatura->inicio)->format('d/m/Y') }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Fim:</strong> {{ \Carbon\Carbon::parse($selectedFormatura->fim)->format('d/m/Y') }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Data da Formatura:</strong> {{ $selectedFormatura->formatura ? \Carbon\Carbon::parse($selectedFormatura->formatura)->format('d/m/Y') : 'Não informado' }}</p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Lista:</strong>
                                        @if ($selectedFormatura->lista)
                                            <a href="{{ Storage::disk('public_uploads')->url($selectedFormatura->lista) }}" target="_blank" class="flex items-center gap-2 text-blue-500 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ basename($selectedFormatura->lista) }}
                                            </a>
                                        @else
                                            Não informado
                                        @endif
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Conteúdo:</strong>
                                        @if ($selectedFormatura->conteudo)
                                            <a href="{{ Storage::disk('public_Uploads')->url($selectedFormatura->conteudo) }}" target="_blank" class="flex items-center gap-2 text-blue-500 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ basename($selectedFormatura->conteudo) }}
                                            </a>
                                        @else
                                            Não informado
                                        @endif
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300"><strong>Ofício:</strong>
                                        @if ($selectedFormatura->oficio)
                                            <a href="{{ Storage::disk('public_Uploads')->url($selectedFormatura->oficio) }}" target="_blank" class="flex items-center gap-2 text-blue-500 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ basename($selectedFormatura->oficio) }}
                                            </a>
                                        @else
                                            Não informado
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Erro</h3>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-6">Não foi possível carregar os dados da formatura.</p>
                                <div class="flex justify-end">
                                    <button wire:click="closeViewModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                        Fechar
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Modal de Confirmação de Exclusão -->
                @if ($confirmDeleteId)
                    <div wire:key="delete-modal" x-data="{ open: {{ $confirmDeleteId ? 'true' : 'false' }} }" x-show="open" x-on:keydown.escape.window="open && $wire.set('confirmDeleteId', null)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" @click.self="$wire.set('confirmDeleteId', null)" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Confirmar Exclusão</h3>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">Tem certeza que deseja apagar esta formatura? Esta ação não pode ser desfeita.</p>
                            <div class="flex justify-end gap-2">
                                <button wire:click="$set('confirmDeleteId', null)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                    Cancelar
                                </button>
                                <button wire:click="delete" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
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
                                    <th scope="col" wire:click="sortBy('presidio_id')" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Presídio @if ($sortField === 'presidio_id') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Curso
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Instrutor
                                    </th>
                                    <th scope="col" wire:click="sortBy('inicio')" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Início @if ($sortField === 'inicio') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                    </th>
                                    <th scope="col" wire:click="sortBy('fim')" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Fim @if ($sortField === 'fim') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                    </th>
                                    <th scope="col" wire:click="sortBy('formatura')" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Formatura @if ($sortField === 'formatura') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Ação
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $formatura)
                                    <tr wire:key="table-row-{{ $formatura->id }}-{{ $index }}" class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }}">
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]" title="{{ $formatura->presidio->nome }}">{{ $formatura->presidio->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]" title="{{ $formatura->curso->nome }}">{{ $formatura->curso->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]" title="{{ $formatura->instrutor->nome }}">{{ $formatura->instrutor->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($formatura->inicio)->format('d/m/Y') }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($formatura->fim)->format('d/m/Y') }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $formatura->formatura ? \Carbon\Carbon::parse($formatura->formatura)->format('d/m/Y') : 'N/A' }}</td>
                                        <td class="border px-4 py-3 flex gap-2">
                                            <button wire:click="view({{ $formatura->id }})" class="bg-green-500 hover:bg-green-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1" aria-label="Ver formatura">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </button>
                                            <button wire:click="edit({{ $formatura->id }})" class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1" aria-label="Editar formatura">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $formatura->id }})" class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-2 rounded flex items-center gap-1" aria-label="Apagar formatura">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
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
                        @foreach ($results as $index => $formatura)
                            <div wire:key="card-{{ $formatura->id }}-{{ $index }}" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $formatura->presidio->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Curso: {{ $formatura->curso->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Instrutor: {{ $formatura->instrutor->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Início: {{ \Carbon\Carbon::parse($formatura->inicio)->format('d/m/Y') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Fim: {{ \Carbon\Carbon::parse($formatura->fim)->format('d/m/Y') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Formatura: {{ $formatura->formatura ? \Carbon\Carbon::parse($formatura->formatura)->format('d/m/Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="view({{ $formatura->id }})" class="bg-green-500 hover:bg-green-700 text-white p-2 rounded" aria-label="Ver formatura">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $formatura->id }})" class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded" aria-label="Editar formatura">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $formatura->id }})" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded" aria-label="Apagar formatura">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12" />
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
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">Nenhuma formatura encontrada. Use o campo acima para pesquisar ou crie uma nova.</p>
                @endif
            </div>
        </div>
    </div>
</div>