@section('title', 'Instrutores')

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
                    Criar novo Instrutor
                </button>

                <div class="my-4">
                    <form wire:submit.prevent="search" class="max-w-lg mx-auto">
                        <div class="flex">
                            <input type="text" wire:model.live="searchTerm"
                                class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Pesquisar por nome do instrutor...">
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
                                {{ $instrutor_id ? 'Editar Instrutor' : 'Criar Instrutor' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Bloco -->
                                    <div>
                                        <label for="bloco_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bloco</label>
                                        <select id="bloco_id" wire:model="bloco_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            required>
                                            <option value="">--selecione--</option>
                                            @foreach ($blocoOptions as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="bloco_id" class="mt-2" />
                                    </div>
                                    <!-- Categoria -->
                                    <div>
                                        <label for="categoria_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                        <select id="categoria_id" wire:model="categoria_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            required>
                                            <option value="">--selecione--</option>
                                            @foreach ($categoriaOptions as $id => $nome)
                                                <option value="{{ $id }}">{{ $nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="categoria_id" class="mt-2" />
                                    </div>
                                    <!-- Nome -->
                                    <div>
                                        <label for="nome"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                        <input id="nome" type="text" wire:model="nome"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 capitalize"
                                            placeholder="ex: João Batista" required>
                                        <x-input-error for="nome" class="mt-2" />
                                    </div>
                                    <!-- Telefone -->
                                    <div>
                                        <label for="telefone"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                                        <input id="telefone" type="text" wire:model="telefone"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: (71) 9 9999-0000" required>
                                        <x-input-error for="telefone" class="mt-2" />
                                    </div>
                                    <!-- Igreja -->
                                    <div>
                                        <label for="igreja"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja</label>
                                        <input id="igreja" type="text" wire:model="igreja"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: IURD Pituba" required>
                                        <x-input-error for="igreja" class="mt-2" />
                                    </div>
                                    <!-- Profissão -->
                                    <div>
                                        <label for="profissao"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profissão</label>
                                        <input id="profissao" type="text" wire:model="profissao"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Professor" required>
                                        <x-input-error for="profissao" class="mt-2" />
                                    </div>
                                    <!-- Batismo -->
                                    <div class="border border-gray-300 dark:border-gray-600 rounded-md p-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batismo</label>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" wire:model="batismo" value="aguas"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Batizado nas
                                                Águas</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="batismo" value="espirito"
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Batizado no
                                                Espírito Santo</label>
                                        </div>
                                        <x-input-error for="batismo" class="mt-2" />
                                    </div>
                                    <!-- Testemunho -->
                                    <div>
                                        <label for="testemunho"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Testemunho</label>
                                        <textarea id="testemunho" wire:model="testemunho"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Era viciado, etc."></textarea>
                                        <x-input-error for="testemunho" class="mt-2" />
                                    </div>
                                    <!-- Carga Horária -->
                                    <div>
                                        <label for="carga"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carga
                                            Horária</label>
                                        <input id="carga" type="text" wire:model="carga"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: 40 horas">
                                        <x-input-error for="carga" class="mt-2" />
                                    </div>
                                    <!-- Certificado -->
                                    <div class="flex items-center">
                                        <input id="certificado" type="checkbox" wire:model="certificado"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="certificado"
                                            class="ml-2 text-sm text-gray-700 dark:text-gray-300">Certificado</label>
                                        <x-input-error for="certificado" class="mt-2" />
                                    </div>
                                    <!-- Inscrição -->
                                    <div class="flex items-center">
                                        <input id="inscricao" type="checkbox" wire:model="inscricao"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="inscricao"
                                            class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inscrição</label>
                                        <x-input-error for="inscricao" class="mt-2" />
                                    </div>
                                    <!-- Foto -->
                                    <div>
                                        <label for="foto"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto</label>
                                        <input id="foto" type="file" wire:model="foto"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <div wire:loading wire:target="foto"
                                            class="text-sm text-gray-500 italic mt-2">Carregando...</div>
                                        <x-input-error for="foto" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-6">
                                    <button type="button" wire:click="closeModal"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                                        {{ $instrutor_id ? 'Atualizar' : 'Criar' }}
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
                        @if ($selectedInstrutor)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 p-6 overflow-y-auto max-h-[90vh]">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $selectedInstrutor->nome }}</h3>
                                    <button wire:click="closeViewModal"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex flex-col md:flex-row gap-6">
                                    <div class="md:w-1/3">
                                        <img src="{{ url($selectedInstrutor->foto) }}"
                                            alt="Foto de {{ $selectedInstrutor->nome }}"
                                            class="h-40 w-40 rounded-full object-cover object-center mx-auto">
                                        <div class="mt-4">
                                            <h4 class="text-md font-semibold text-blue-500">Contato</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Telefone:</strong>
                                                {{ $selectedInstrutor->telefone }}</p>
                                        </div>
                                        <div class="mt-4">
                                            <h4 class="text-md font-semibold text-blue-500">Localização</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Igreja:</strong>
                                                {{ $selectedInstrutor->igreja ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Bloco:</strong>
                                                {{ optional($selectedInstrutor->bloco)->nome ?? 'Não informado' }}</p>
                                        </div>
                                    </div>
                                    <div class="md:w-2/3">
                                        <div class="mb-4">
                                            <h4 class="text-md font-semibold text-blue-500">Dados Espirituais</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Batizado nas
                                                    Águas?</strong> {{ $selectedInstrutor->batismoAguas }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Batizado no Espírito
                                                    Santo?</strong> {{ $selectedInstrutor->batismoEspirito }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Testemunho:</strong>
                                                <span
                                                    class="italic">{{ $selectedInstrutor->testemunho ?? 'Não informado' }}</span>
                                            </p>
                                        </div>
                                        <div>
                                            <h4 class="text-md font-semibold text-blue-500">Outras Informações</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Profissão:</strong>
                                                {{ $selectedInstrutor->profissao ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Categoria:</strong>
                                                {{ optional($selectedInstrutor->categoria)->nome ?? 'Não informado' }}
                                            </p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Carga Horária:</strong>
                                                {{ $selectedInstrutor->carga ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Certificado:</strong>
                                                {{ $selectedInstrutor->certificado ? 'Sim' : 'Não' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Inscrição:</strong>
                                                {{ $selectedInstrutor->inscricao ? 'Sim' : 'Não' }}</p>
                                        </div>
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
                                    instrutor.</p>
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
                                instrutor? Esta ação não pode ser desfeita.</p>
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
                                        Telefone</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $instrutor)
                                    <tr wire:key="table-row-{{ $instrutor->id }}-{{ $index }}"
                                        class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }}">
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $instrutor->nome }}">{{ $instrutor->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instrutor->telefone }}</td>
                                        <td class="border px-4 py-3 flex gap-2">
                                            <button wire:click="view({{ $instrutor->id }})"
                                                class="bg-green-500 hover:bg-green-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Ver instrutor">
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
                                            <button wire:click="edit({{ $instrutor->id }})"
                                                class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Editar instrutor">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $instrutor->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Apagar instrutor">
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
                        @foreach ($results as $index => $instrutor)
                            <div wire:key="card-{{ $instrutor->id }}-{{ $index }}"
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $instrutor->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Telefone:
                                            {{ $instrutor->telefone }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="view({{ $instrutor->id }})"
                                            class="bg-green-500 hover:bg-green-700 text-white p-2 rounded"
                                            aria-label="Ver instrutor">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $instrutor->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded"
                                            aria-label="Editar instrutor">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $instrutor->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded"
                                            aria-label="Apagar instrutor">
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
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">Nenhum instrutor encontrado. Use o campo
                        acima para pesquisar ou crie um novo.</p>
                @endif
            </div>
        </div>
    </div>
</div>
