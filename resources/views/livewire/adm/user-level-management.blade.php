@section('title', 'Admin')

<x-slot name="header">
    <h2 class="capitalize font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Gestão de Níveis de Usuário
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
    @elseif (!empty($errorMessage))
        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3"
            role="alert">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @elseif (!empty($results))
        <div class="bg-gray-100 dark:bg-gray-700 rounded-b text-gray-900 dark:text-gray-100 px-4 py-3 shadow-md my-3"
            role="alert">
            <p class="text-sm">Resultados encontrados: {{ $results->total() }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="my-4">
                    <form wire:submit.prevent="search" class="max-w-lg mx-auto">
                        <div class="flex">
                            <input type="text" wire:model.live="searchTerm"
                                class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded-l-md w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                placeholder="Pesquisar por nome, email ou nível...">
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
                                usuário? Esta ação não pode ser desfeita.</p>
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
                                    <th scope="col" wire:click="sortBy('name')"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Nome @if ($sortField === 'name')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col" wire:click="sortBy('email')"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200 cursor-pointer">
                                        Email @if ($sortField === 'email')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Nível
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Ação
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $user)
                                    <tr wire:key="table-row-{{ $user->id }}-{{ $index }}"
                                        class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:{{ $index % 2 == 0 ? 'bg-gray-800' : 'bg-gray-900' }}">
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $user->name }}">{{ $user->name }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $user->email }}">{{ $user->email }}</td>
                                        <td class="border px-4 py-3 text-sm text-gray-700 dark:text-gray-200 truncate max-w-[150px]"
                                            title="{{ $user->getRoleNames()->first() ?? 'Nenhum' }}">
                                            {{ $user->getRoleNames()->first() ?? 'Nenhum' }}</td>
                                        <td class="border px-4 py-3 flex gap-2">
                                            <button wire:click="selectUser({{ $user->id }})"
                                                class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Editar usuário">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Editar
                                            </button>
                                            <button wire:click="confirmDelete({{ $user->id }})"
                                                class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-2 rounded flex items-center gap-1"
                                                aria-label="Apagar usuário">
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
                        @foreach ($results as $index => $user)
                            <div wire:key="card-{{ $user->id }}-{{ $index }}"
                                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate max-w-[200px]"
                                            title="{{ $user->name }}">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[200px]"
                                            title="{{ $user->email }}">Email: {{ $user->email }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-[200px]"
                                            title="{{ $user->getRoleNames()->first() ?? 'Nenhum' }}">Nível:
                                            {{ $user->getRoleNames()->first() ?? 'Nenhum' }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="selectUser({{ $user->id }})"
                                            class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded"
                                            aria-label="Editar usuário">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded"
                                            aria-label="Apagar usuário">
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
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">Nenhum usuário encontrado. Use o campo
                        acima para pesquisar.</p>
                @endif

                <!-- Editar Papéis e Permissões -->
                @if ($selectedUserId)
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">Editar Usuário</h2>
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                            <!-- Papéis -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Papéis</h3>
                                @error('roles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                    @foreach ($roles as $role)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="selectedUserRoles" value="{{ $role->name }}"
                                                   class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <button wire:click="updateRoles" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Atualizar Papéis
                                </button>
                            </div>

                            <!-- Permissões -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Permissões</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                                    @foreach ($permissions as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="selectedUserPermissions" value="{{ $permission->name }}"
                                                   class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-500">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <button wire:click="updatePermissions" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Atualizar Permissões
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Criar Novo Papel -->
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">Criar Novo Papel</h2>
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <input type="text" wire:model="newRoleName" placeholder="Nome do novo papel"
                               class="border rounded px-3 py-2 w-full mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                        @error('newRoleName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <button wire:click="createRole" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Criar Papel
                        </button>
                    </div>
                </div>

                <!-- Criar Nova Permissão -->
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">Criar Nova Permissão</h2>
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                        <input type="text" wire:model="newPermissionName" placeholder="Nome da nova permissão"
                               class="border rounded px-3 py-2 w-full mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                        @error('newPermissionName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <button wire:click="createPermission" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Criar Permissão
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>