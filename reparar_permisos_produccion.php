<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== REPARACIÓN DE PERMISOS PARA PRODUCCIÓN ===\n";

// 1. Crear todos los permisos necesarios
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

echo "Creando permisos...\n";
foreach ($permissions as $permission) {
    $perm = Permission::firstOrCreate(['name' => $permission]);
    echo "✓ Permiso '{$permission}' creado/verificado\n";
}

// 2. Crear roles
echo "\nCreando roles...\n";
$adminRole = Role::firstOrCreate(['name' => 'admin']);
$userRole = Role::firstOrCreate(['name' => 'user']);
echo "✓ Roles 'admin' y 'user' creados/verificados\n";

// 3. Asignar TODOS los permisos al rol admin
echo "\nAsignando permisos al rol admin...\n";
$adminRole->syncPermissions($permissions);
echo "✓ Todos los permisos asignados al rol 'admin'\n";

// 4. Asignar algunos permisos básicos al rol user
$userPermissions = [
    'ver-productos',
    'crear-productos', // También usuarios normales pueden crear productos
    'editar-productos',
];
$userRole->syncPermissions($userPermissions);
echo "✓ Permisos básicos asignados al rol 'user'\n";

// 5. Asegurar que todos los usuarios admin tengan el rol correcto
echo "\nAsignando rol admin a usuarios...\n";
$users = User::all();
foreach ($users as $user) {
    if (!$user->hasRole('admin')) {
        $user->assignRole('admin');
        echo "✓ Rol 'admin' asignado a usuario: {$user->name}\n";
    } else {
        echo "- Usuario {$user->name} ya tiene rol 'admin'\n";
    }
}

// 6. Verificación final
echo "\n=== VERIFICACIÓN FINAL ===\n";
$firstUser = User::first();
if ($firstUser) {
    echo "Usuario de prueba: {$firstUser->name}\n";
    echo "Roles: " . $firstUser->roles->pluck('name')->join(', ') . "\n";
    echo "¿Puede crear productos? " . ($firstUser->can('crear-productos') ? 'SÍ' : 'NO') . "\n";
    echo "¿Puede editar productos? " . ($firstUser->can('editar-productos') ? 'SÍ' : 'NO') . "\n";
    echo "¿Puede eliminar productos? " . ($firstUser->can('eliminar-productos') ? 'SÍ' : 'NO') . "\n";
}

echo "\n=== REPARACIÓN COMPLETADA ===\n";
echo "IMPORTANTE: Ejecuta este mismo script en tu servidor de producción.\n";
