<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== VERIFICACIÓN DE PERMISOS ===\n";

$user = User::first();
if ($user) {
    echo "Usuario: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
    echo "\nPermisos:\n";
    $permissions = $user->getAllPermissions()->pluck('name');
    foreach ($permissions as $permission) {
        echo "  - " . $permission . "\n";
    }
    
    echo "\n¿Tiene permiso 'crear-productos'? " . ($user->can('crear-productos') ? 'SÍ' : 'NO') . "\n";
} else {
    echo "No hay usuarios en la base de datos\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
