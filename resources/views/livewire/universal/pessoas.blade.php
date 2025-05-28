@section('title', 'Pessoas')

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
                    Criar nova Pessoa
                </button>

                <div class="my-4">
                    <form wire:submit.prevent="search" class="max-w-lg mx-auto">
                        <div class="flex">
                            <input type="text" wire:model.live="searchTerm"
                                class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Pesquisar por nome da pessoa...">
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
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4 p-6 overflow-y-auto max-h-[90vh]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                {{ $pessoa_id ? 'Editar Pessoa' : 'Criar Pessoa' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Bloco -->
                                    <div>
                                        <label for="bloco_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bloco</label>
                                        <select id="bloco_id" wire:change="FiterRegiaoByBlocoId" wire:model="bloco_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="">--selecione--</option>
                                            @foreach ($blocos as $bloco)
                                                <option value="{{ $bloco->id }}">{{ $bloco->nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="bloco_id" class="mt-2" />
                                    </div>
                                    <!-- Região -->
                                    @if ($regiaos)
                                        <div>
                                            <label for="regiao_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Região</label>
                                            <select id="regiao_id" wire:change="FiterIgrejaByRegiaoId"
                                                wire:model="regiao_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                <option value="">--selecione--</option>
                                                @foreach ($regiaos as $regiao)
                                                    <option value="{{ $regiao->id }}">{{ $regiao->nome }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error for="regiao_id" class="mt-2" />
                                        </div>
                                    @endif
                                    <!-- Igreja -->
                                    @if ($igrejas)
                                        <div>
                                            <label for="igreja_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja</label>
                                            <select id="igreja_id" wire:model="igreja_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                <option value="">--selecione--</option>
                                                @foreach ($igrejas as $igreja)
                                                    <option value="{{ $igreja->id }}">{{ $igreja->nome }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error for="igreja_id" class="mt-2" />
                                        </div>
                                    @endif
                                    <!-- Categoria -->
                                    <div>
                                        <label for="categoria_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                        <select id="categoria_id" wire:model="categoria_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="">--selecione--</option>
                                            @foreach (\App\Models\Universal\Categoria::all() as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="categoria_id" class="mt-2" />
                                    </div>
                                    <!-- Cargo -->
                                    <div>
                                        <label for="cargo_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cargo</label>
                                        <select id="cargo_id" wire:model="cargo_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="">--selecione--</option>
                                            @foreach (\App\Models\Unp\Cargo::all() as $cargo)
                                                <option value="{{ $cargo->id }}">{{ $cargo->nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="cargo_id" class="mt-2" />
                                    </div>
                                    <!-- Grupo -->
                                    <div>
                                        <label for="grupo_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                                        <select id="grupo_id" wire:model="grupo_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="">--selecione--</option>
                                            @foreach (\App\Models\Unp\Grupo::all() as $grupo)
                                                <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="grupo_id" class="mt-2" />
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
                                    <!-- Celular -->
                                    <div>
                                        <label for="celular"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Celular</label>
                                        <input id="celular" type="text" wire:model="celular"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: (71)9 9999-0000" required>
                                        <x-input-error for="celular" class="mt-2" />
                                    </div>
                                    <!-- Email -->
                                    <div>
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input id="email" type="email" wire:model="email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: exemplo@exemplo.com">
                                        <x-input-error for="email" class="mt-2" />
                                    </div>
                                    <!-- Endereço -->
                                    <div>
                                        <label for="endereco"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                                        <input id="endereco" type="text" wire:model="endereco"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Av. ACM, 4197" required>
                                        <x-input-error for="endereco" class="mt-2" />
                                    </div>
                                    <!-- Bairro -->
                                    <div>
                                        <label for="bairro"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bairro</label>
                                        <input id="bairro" type="text" wire:model="bairro"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Pituba" required>
                                        <x-input-error for="bairro" class="mt-2" />
                                    </div>
                                    <!-- CEP -->
                                    <div>
                                        <label for="cep"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">CEP</label>
                                        <input id="cep" type="text" wire:model="cep"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: 40.000-000">
                                        <x-input-error for="cep" class="mt-2" />
                                    </div>
                                    <!-- Estado -->
                                    <div>
                                        <label for="estado_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                        <select id="estado_id" wire:change="FiterRegiaoByEstadoId"
                                            wire:model="estado_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                            <option value="">--selecione--</option>
                                            @foreach ($estados as $estado)
                                                <option value="{{ $estado->id }}">{{ $estado->nome }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="estado_id" class="mt-2" />
                                    </div>
                                    <!-- Cidade -->
                                    @if ($cidades)
                                        <div>
                                            <label for="cidade_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cidade</label>
                                            <select id="cidade_id" wire:model="cidade_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                                <option value="">--selecione--</option>
                                                @foreach ($cidades as $cidade)
                                                    <option value="{{ $cidade->id }}">{{ $cidade->nome }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error for="cidade_id" class="mt-2" />
                                        </div>
                                    @endif
                                    <!-- Profissão -->
                                    <div>
                                        <label for="profissao"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profissão</label>
                                        <input id="profissao" type="text" wire:model="profissao"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Advogado" required>
                                        <x-input-error for="profissao" class="mt-2" />
                                    </div>
                                    <!-- Aptidões -->
                                    <div>
                                        <label for="aptidoes"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aptidões</label>
                                        <input id="aptidoes" type="text" wire:model="aptidoes"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: elétrica, marcenaria, costura, etc.">
                                        <x-input-error for="aptidoes" class="mt-2" />
                                    </div>
                                    <!-- Conversão -->
                                    <div>
                                        <label for="conversao"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conversão</label>
                                        <input id="conversao" type="date" wire:model="conversao"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <x-input-error for="conversao" class="mt-2" />
                                    </div>
                                    <!-- Obra -->
                                    <div>
                                        <label for="obra"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Obra</label>
                                        <input id="obra" type="date" wire:model="obra"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                        <x-input-error for="obra" class="mt-2" />
                                    </div>
                                    <!-- Trabalho -->
                                    <div class="col-span-1 md:col-span-2 border rounded-md p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trabalho
                                        </p>
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="trabalho" value="interno"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Trabalho
                                                    Interno (Credenciado)</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="trabalho" value="externo"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Trabalho
                                                    Externo</label>
                                            </div>
                                        </div>
                                        <x-input-error for="trabalho" class="mt-2" />
                                    </div>
                                    <!-- Batismo -->
                                    <div class="col-span-1 md:col-span-2 border rounded-md p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batismo
                                        </p>
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="batismo" value="aguas"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Batizado
                                                    nas Águas</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="batismo" value="espirito"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Batizado
                                                    no Espírito Santo</label>
                                            </div>
                                        </div>
                                        <x-input-error for="batismo" class="mt-2" />
                                    </div>
                                    <!-- Preso -->
                                    <div class="col-span-1 md:col-span-2 border rounded-md p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preso</p>
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="preso" value="preso"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Já foi
                                                    Preso</label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="preso" value="familiar"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tem
                                                    familiar Preso</label>
                                            </div>
                                        </div>
                                        <x-input-error for="preso" class="mt-2" />
                                    </div>
                                    <!-- Testemunho -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="testemunho"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Testemunho</label>
                                        <textarea id="testemunho" wire:model="testemunho"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                                            placeholder="ex: Era viciado, etc."></textarea>
                                        <x-input-error for="testemunho" class="mt-2" />
                                    </div>
                                    <!-- Foto -->
                                    <div class="col-span-1 md:col-span-2">
                                        <label for="foto"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto</label>
                                        <input id="foto" type="file" wire:model="foto"
                                            class="mt-1 block w-full text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                                        <div wire:loading wire:target="foto" class="text-sm text-gray-500 italic">
                                            Carregando...</div>
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
                                        {{ $pessoa_id ? 'Atualizar' : 'Criar' }}
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
                        @if ($selectedPessoa)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 p-6 overflow-y-auto max-h-[90vh]">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes de
                                        {{ $selectedPessoa->nome }}</h3>
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
                                    <!-- Foto e Informações Principais -->
                                    <div class="md:w-1/3 flex flex-col items-center">
                                        <img src="{{ url($selectedPessoa->foto) }}"
                                            alt="Foto de {{ $selectedPessoa->nome }}"
                                            class="h-40 w-40 rounded-full object-cover object-center mb-4">
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $selectedPessoa->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $selectedPessoa->profissao ?? 'Sem profissão informada' }}</p>
                                    </div>
                                    <!-- Detalhes -->
                                    <div class="md:w-2/3">
                                        <!-- Contato -->
                                        <div class="mb-6">
                                            <h4 class="text-md font-semibold text-blue-500">Contato</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong>
                                                {{ $selectedPessoa->email ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Celular:</strong>
                                                {{ $selectedPessoa->celular ?? 'Não informado' }}</p>
                                            @if ($selectedPessoa->telefone)
                                                <p class="text-gray-700 dark:text-gray-300"><strong>Telefone:</strong>
                                                    {{ $selectedPessoa->telefone }}</p>
                                            @endif
                                        </div>
                                        <!-- Endereço -->
                                        <div class="mb-6">
                                            <h4 class="text-md font-semibold text-blue-500">Endereço</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ $selectedPessoa->endereco ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ $selectedPessoa->bairro ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ $selectedPessoa->cep ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ optional($selectedPessoa->cidade)->nome ?? 'Não informado' }},
                                                {{ optional($selectedPessoa->estado)->nome ?? 'Não informado' }}</p>
                                        </div>
                                        <!-- Dados Espirituais -->
                                        <div class="mb-6">
                                            <h4 class="text-md font-semibold text-blue-500">Dados Espirituais</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Conversão:</strong>
                                                {{ $selectedPessoa->conversao ? \Carbon\Carbon::parse($selectedPessoa->conversao)->format('d/m/Y') : 'Não informado' }}
                                            </p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Obra:</strong>
                                                {{ $selectedPessoa->obra ? \Carbon\Carbon::parse($selectedPessoa->obra)->format('d/m/Y') : 'Não informado' }}
                                            </p>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ $selectedPessoa->trabalhoTexto }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Batizado nas
                                                    Águas?</strong> {{ $selectedPessoa->batismoAguas }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Batizado no Espírito
                                                    Santo?</strong> {{ $selectedPessoa->batismoEspirito }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Já foi preso?</strong>
                                                {{ $selectedPessoa->jaFoiPreso }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Tem familiar
                                                    preso?</strong> {{ $selectedPessoa->familiarPreso }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Testemunho:</strong>
                                                <span
                                                    class="italic">{{ $selectedPessoa->testemunho ?? 'Não informado' }}</span>
                                            </p>
                                        </div>
                                        <!-- Outras Informações -->
                                        <div>
                                            <h4 class="text-md font-semibold text-blue-500">Outras Informações</h4>
                                            <div class="border-t-2 border-blue-500 my-2"></div>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Aptidões:</strong>
                                                {{ $selectedPessoa->aptidoes ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Grupo:</strong>
                                                {{ optional($selectedPessoa->grupo)->nome ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Cargo:</strong>
                                                {{ optional($selectedPessoa->cargo)->nome ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Categoria:</strong>
                                                {{ optional($selectedPessoa->categoria)->nome ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Bloco:</strong>
                                                {{ optional($selectedPessoa->bloco)->nome ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Região:</strong>
                                                {{ optional($selectedPessoa->regiao)->nome ?? 'Não informado' }}</p>
                                            <p class="text-gray-700 dark:text-gray-300"><strong>Igreja:</strong>
                                                {{ optional($selectedPessoa->igreja)->nome ?? 'Não informado' }}</p>
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
                                <p class="text-gray-600 dark:text-gray-300 mb-6">Não foi possível carregar os dados da
                                    pessoa.</p>
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
                            <p class="text-gray-600 dark:text-gray-300 mb-6">Tem certeza que deseja apagar esta pessoa?
                                Esta ação não pode ser desfeita.</p>
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
                                        Celular</th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $pessoa)
                                    <tr wire:key="table-row-{{ $pessoa->id }}-{{ $index }}"
                                        class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }}">
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $pessoa->nome }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $pessoa->celular }}</td>
                                        <td class="border px-4 py-3 flex gap-2">
                                            <button wire:click="view({{ $pessoa->id }})"
                                                class="bg-green-500 hover:bg-green-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Ver pessoa">
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
                                            <button wire:click="edit({{ $pessoa->id }})"
                                                class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Editar pessoa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $pessoa->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Apagar pessoa">
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
                        @foreach ($results as $index => $pessoa)
                            <div wire:key="card-{{ $pessoa->id }}-{{ $index }}"
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $pessoa->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Celular:
                                            {{ $pessoa->celular }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="view({{ $pessoa->id }})"
                                            class="bg-green-500 hover:bg-green-700 text-white p-2 rounded"
                                            aria-label="Ver pessoa">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $pessoa->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded"
                                            aria-label="Editar pessoa">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $pessoa->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded"
                                            aria-label="Apagar pessoa">
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
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">Nenhuma pessoa encontrada. Use o campo
                        acima para pesquisar ou crie uma nova.</p>
                @endif
            </div>
        </div>
    </div>
</div>
