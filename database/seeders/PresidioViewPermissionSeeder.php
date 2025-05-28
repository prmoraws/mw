<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PresidioViewPermissionSeeder extends Seeder
{
    public function run()
    {
        // Criar a permissão view presidios
        $permission = Permission::firstOrCreate([
            'name' => 'view presidios',
            'guard_name' => 'web',
        ]);

        // Atribuir a permissão aos papéis unp e adm
        $roles = ['unp', 'adm'];
        foreach ($roles as $roleName) {
            $role = Role::findByName($roleName, 'web');
            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
    }
}