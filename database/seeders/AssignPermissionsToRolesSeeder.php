<?php

// database/seeders/AssignPermissionsToRoleSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRolesSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();

        $permissions = Permission::whereIn('name', [
            // Gestión general
            'gestionar-roles',
            'gestionar-permisos',
            'asignar-permisos-roles',
            'asignar-roles-usuarios',

            // Usuarios
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',

            // Clientes
            'ver-clientes',
            'crear-clientes',
            'editar-clientes',
            'eliminar-clientes',

            // Reclamos
            'ver-reclamos',
            'crear-reclamos',
            'editar-reclamos',
            'eliminar-reclamos',

            // Blogs
            'crear-blogs',
            'editar-blogs',
            'eliminar-blogs',

            // Productos
            'ver-productos',
            'crear-productos',
            'editar-productos',
            'eliminar-productos',

            // Tarjetas
            'crear-tarjetas',
            'editar-tarjetas',
            'eliminar-tarjetas',
        ])->pluck('id');

        $adminRole->syncPermissions($permissions);
    }
}

