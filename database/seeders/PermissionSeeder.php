<?php

// database/seeders/PermissionSeeder.php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
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
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}
