<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignRoleToUserSeeder extends Seeder
{
    public function run()
    {
        // Cambia esto si identificas al usuario por email
        $user = User::where('name', 'Admin')->first();

        if (!$user) {
            echo "Usuario 'Admin' no encontrado.\n";
            return;
        }

        // Asigna el rol 'admin' si no lo tiene aún
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
            echo "Rol 'admin' asignado al usuario '{$user->name}'.\n";
        } else {
            echo "El usuario '{$user->name}' ya tiene el rol 'admin'.\n";
        }
    }
}
