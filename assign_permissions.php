<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== ASIGNACIÓN DE PERMISOS ===\n";

// Crear permisos si no existen
$permissions = [
    'crear-productos',
    'editar-productos',
    'eliminar-productos',
    'ver-productos'
];

foreach ($permissions as $perm) {
    $permission = Permission::firstOrCreate(['name' => $perm]);
    echo "Permiso '{$perm}' creado/verificado\n";
}

// Obtener o crear rol admin
$adminRole = Role::firstOrCreate(['name' => 'admin']);
echo "Rol 'admin' creado/verificado\n";

// Asignar permisos al rol admin
$adminRole->syncPermissions($permissions);
echo "Permisos asignados al rol 'admin'\n";

// Asignar rol admin a todos los usuarios (o al primer usuario)
$users = User::all();
foreach ($users as $user) {
    if (!$user->hasRole('admin')) {
        $user->assignRole('admin');
        echo "Rol 'admin' asignado a usuario: {$user->name}\n";
    }
}

echo "\n=== VERIFICACIÓN FINAL ===\n";
$firstUser = User::first();
if ($firstUser) {
    echo "Usuario: {$firstUser->name}\n";
    echo "Roles: " . $firstUser->roles->pluck('name')->join(', ') . "\n";
    echo "¿Puede crear productos? " . ($firstUser->can('crear-productos') ? 'SÍ' : 'NO') . "\n";
}

echo "\n=== PROCESO COMPLETADO ===\n"; 