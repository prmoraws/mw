<?php

namespace App\Livewire\Adm;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class UserLevelManagement extends Component
{
    use WithPagination;

    public $users;
    public $roles;
    public $permissions;
    public $selectedUserId;
    public $selectedUserRoles = [];
    public $selectedUserPermissions = [];
    public $newRoleName = '';
    public $newPermissionName = '';
    public $searchTerm = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmDeleteId = null;

    public function mount()
    {
        $this->users = User::all();
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        $this->selectedUserRoles = $user->getRoleNames()->toArray();
        $this->selectedUserPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        Log::info('UserLevelManagement: Usuário selecionado', [
            'user_id' => $userId,
            'roles' => $this->selectedUserRoles,
            'permissions' => $this->selectedUserPermissions,
        ]);
    }

    public function updateRoles()
    {
        $user = User::find($this->selectedUserId);
        if ($user) {
            if ($user->hasPermissionTo('superadmin') && !in_array('adm', $this->selectedUserRoles)) {
                $this->addError('roles', 'Não é possível remover o papel adm de um superadmin.');
                return;
            }

            $user->syncRoles($this->selectedUserRoles);
            session()->flash('message', 'Papéis atualizados com sucesso!');
            Log::info('UserLevelManagement: Papéis atualizados', [
                'user_id' => $this->selectedUserId,
                'roles' => $this->selectedUserRoles,
            ]);
        }
    }

    public function updatePermissions()
    {
        $user = User::find($this->selectedUserId);
        if ($user) {
            $user->syncPermissions($this->selectedUserPermissions);
            session()->flash('message', 'Permissões atualizadas com sucesso!');
            Log::info('UserLevelManagement: Permissões atualizadas', [
                'user_id' => $this->selectedUserId,
                'permissions' => $this->selectedUserPermissions,
            ]);
        }
    }

    public function createRole()
    {
        $this->validate([
            'newRoleName' => 'required|unique:roles,name',
        ]);

        Role::create(['name' => $this->newRoleName]);
        $this->roles = Role::all();
        $this->newRoleName = '';
        session()->flash('message', 'Papel criado com sucesso!');
        Log::info('UserLevelManagement: Papel criado', ['role' => $this->newRoleName]);
    }

    public function createPermission()
    {
        $this->validate([
            'newPermissionName' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->newPermissionName]);
        $this->permissions = Permission::all();
        $this->newPermissionName = '';
        session()->flash('message', 'Permissão criada com sucesso!');
        Log::info('UserLevelManagement: Permissão criada', ['permission' => $this->newPermissionName]);
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmDeleteId) {
            $user = User::find($this->confirmDeleteId);
            if ($user) {
                if ($user->hasPermissionTo('superadmin')) {
                    $this->addError('errorMessage', 'Não é possível excluir um usuário com permissão superadmin.');
                    $this->confirmDeleteId = null;
                    return;
                }
                $user->delete();
                session()->flash('message', 'Usuário deletado com sucesso!');
                Log::info('UserLevelManagement: Usuário deletado', ['user_id' => $this->confirmDeleteId]);
            }
            $this->confirmDeleteId = null;
            $this->selectedUserId = null;
            $this->selectedUserRoles = [];
            $this->selectedUserPermissions = [];
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $results = User::query()
            ->when($this->searchTerm, function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('roles', function ($q) {
                        $q->where('name', 'like', '%' . $this->searchTerm . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.adm.user-level-management', [
            'results' => $results,
        ]);
    }
}