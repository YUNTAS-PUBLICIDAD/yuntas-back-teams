<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== DIAGNÓSTICO COMPLETO DE PERMISOS ===\n";

// 1. Verificar que las tablas de permisos existen
try {
    $permissions = Permission::all();
    $roles = Role::all();
    echo "✓ Tablas de permisos funcionando correctamente\n";
} catch (Exception $e) {
    echo "✗ ERROR: Tablas de permisos no funcionan: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Listar todos los permisos disponibles
echo "\n=== PERMISOS EN EL SISTEMA ===\n";
foreach ($permissions as $permission) {
    echo "- {$permission->name}\n";
}

// 3. Listar todos los roles disponibles  
echo "\n=== ROLES EN EL SISTEMA ===\n";
foreach ($roles as $role) {
    echo "- {$role->name}\n";
}

// 4. Verificar usuarios
echo "\n=== USUARIOS Y SUS PERMISOS ===\n";
$users = User::all();
foreach ($users as $user) {
    echo "Usuario: {$user->name} ({$user->email})\n";
    echo "  Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
    echo "  Permisos directos: " . $user->permissions->pluck('name')->join(', ') . "\n";
    echo "  Todos los permisos: " . $user->getAllPermissions()->pluck('name')->join(', ') . "\n";
    echo "  ¿Puede crear productos? " . ($user->can('crear-productos') ? 'SÍ' : 'NO') . "\n";
    echo "  ¿Tiene rol admin? " . ($user->hasRole('admin') ? 'SÍ' : 'NO') . "\n";
    echo "\n";
}

// 5. Verificar permisos del rol admin
echo "=== PERMISOS DEL ROL ADMIN ===\n";
$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    echo "Permisos asignados al rol admin:\n";
    foreach ($adminRole->permissions as $permission) {
        echo "- {$permission->name}\n";
    }
} else {
    echo "✗ ROL 'admin' NO EXISTE\n";
}

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
