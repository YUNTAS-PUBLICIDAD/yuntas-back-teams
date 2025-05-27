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
            'gestionar-roles',
            'gestionar-permisos',
            'asignar-permisos-roles',
        ])->pluck('id');

        $adminRole->syncPermissions($permissions);
    }
}

