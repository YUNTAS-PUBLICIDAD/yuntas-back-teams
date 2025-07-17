<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "=== DIAGNÓSTICO PRODUCCIÓN YUNTAS ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
echo "Entorno: " . config('app.env') . "\n";
echo "URL: " . config('app.url') . "\n\n";

// 1. Verificar conexión a base de datos
try {
    DB::connection()->getPdo();
    echo "✓ Conexión a base de datos: OK\n";
} catch (Exception $e) {
    echo "✗ ERROR conexión BD: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Verificar tablas de permisos
$tables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles', 'role_has_permissions'];
foreach ($tables as $table) {
    try {
        DB::table($table)->count();
        echo "✓ Tabla {$table}: OK\n";
    } catch (Exception $e) {
        echo "✗ ERROR tabla {$table}: " . $e->getMessage() . "\n";
    }
}

// 3. Verificar permisos y roles
echo "\n=== PERMISOS Y ROLES ===\n";
$permissionsCount = Permission::count();
$rolesCount = Role::count();
echo "Permisos totales: {$permissionsCount}\n";
echo "Roles totales: {$rolesCount}\n";

// Listar permisos específicos de productos
$productPermissions = Permission::where('name', 'like', '%producto%')->get();
echo "\nPermisos de productos:\n";
foreach ($productPermissions as $perm) {
    echo "- {$perm->name}\n";
}

// 4. Verificar usuarios
echo "\n=== USUARIOS ===\n";
$users = User::with(['roles', 'permissions'])->get();
foreach ($users as $user) {
    echo "Usuario: {$user->name} ({$user->email})\n";
    echo "  ID: {$user->id}\n";
    echo "  Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
    echo "  Permisos directos: " . $user->permissions->pluck('name')->join(', ') . "\n";
    
    // Verificar permisos específicos
    $canCreateProducts = $user->can('crear-productos');
    $hasAdminRole = $user->hasRole('admin');
    $hasUserRole = $user->hasRole('user');
    
    echo "  ¿Puede crear productos? " . ($canCreateProducts ? 'SÍ' : 'NO') . "\n";
    echo "  ¿Tiene rol admin? " . ($hasAdminRole ? 'SÍ' : 'NO') . "\n";
    echo "  ¿Tiene rol user? " . ($hasUserRole ? 'SÍ' : 'NO') . "\n";
    echo "\n";
}

// 5. Verificar rol admin específicamente
echo "=== ROL ADMIN DETALLADO ===\n";
$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    echo "Rol admin encontrado (ID: {$adminRole->id})\n";
    echo "Permisos del rol admin:\n";
    foreach ($adminRole->permissions as $permission) {
        echo "- {$permission->name}\n";
    }
    
    // Verificar usuarios con rol admin
    $adminUsers = User::role('admin')->get();
    echo "\nUsuarios con rol admin: " . $adminUsers->count() . "\n";
    foreach ($adminUsers as $user) {
        echo "- {$user->name} ({$user->email})\n";
    }
} else {
    echo "✗ ROL 'admin' NO EXISTE\n";
}

// 6. Verificar cache de permisos
echo "\n=== CACHE DE PERMISOS ===\n";
$cacheKey = config('permission.cache.key');
echo "Cache key: {$cacheKey}\n";

// Limpiar cache de permisos
try {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    echo "✓ Cache de permisos limpiado\n";
} catch (Exception $e) {
    echo "✗ Error limpiando cache: " . $e->getMessage() . "\n";
}

// 7. Verificar configuración de permisos
echo "\n=== CONFIGURACIÓN ===\n";
echo "Guard por defecto: " . config('auth.defaults.guard') . "\n";
echo "Provider por defecto: " . config('auth.defaults.provider') . "\n";
echo "Model User: " . config('auth.providers.users.model') . "\n";
echo "Permission model: " . config('permission.models.permission') . "\n";
echo "Role model: " . config('permission.models.role') . "\n";

echo "\n=== DIAGNÓSTICO COMPLETADO ===\n";
echo "Para usar en producción: scp este archivo al servidor y ejecutar: php diagnostico_produccion.php\n";
