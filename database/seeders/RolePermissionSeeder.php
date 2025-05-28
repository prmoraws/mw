<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Criar papéis
        $adm = Role::create(['name' => 'adm']);
        $unp = Role::create(['name' => 'unp']);
        $evento = Role::create(['name' => 'evento']);
        $universal = Role::create(['name' => 'universal']);

        // Criar permissões específicas para cada grupo de rotas
        $permissions = [
            // Permissões para UNP
            'view unp dashboard' => ['unp', 'adm'],
            'view cursos' => ['unp', 'adm'],
            'view formaturas' => ['unp', 'adm'],
            'view instrutores' => ['unp', 'adm'],
            'view reeducandos' => ['unp', 'adm'],
            'view cargos' => ['unp', 'adm'],
            'view grupos' => ['unp', 'adm'],

            // Permissões para Evento
            'view evento dashboard' => ['evento', 'adm'],
            'view terreiros' => ['evento', 'adm'],
            'view instituicoes' => ['evento', 'adm'],
            'view cestas' => ['evento', 'adm'],
            'view entregas' => ['evento', 'adm'],

            // Permissões para Universal
            'view universal dashboard' => ['universal', 'adm'],
            'view categorias' => ['universal', 'adm'],
            'view blocos' => ['universal', 'adm'],
            'view regiaos' => ['universal', 'adm'],
            'view igrejas' => ['universal', 'adm'],
            'view pessoas' => ['universal', 'adm'],
            'view pastores' => ['universal', 'adm'],
            'view banners' => ['universal', 'adm'],

            // Permissões gerais
            'view dashboard' => ['adm', 'unp', 'evento', 'universal'], // Dashboard principal
            'view documentacao' => ['adm', 'unp', 'evento', 'universal'], // Documentação
            'view adm dashboard' => ['adm'], // Dashboard ADM
        ];

        // Criar permissões e atribuí-las aos papéis
        foreach ($permissions as $permission => $roles) {
            Permission::create(['name' => $permission]);
            foreach ($roles as $role) {
                Role::findByName($role)->givePermissionTo($permission);
            }
        }
    }
}