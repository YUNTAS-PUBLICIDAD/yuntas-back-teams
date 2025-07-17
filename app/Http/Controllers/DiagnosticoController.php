<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class DiagnosticoController extends Controller
{
    public function diagnostico()
    {
        $output = [];
        $output[] = "=== DIAGNÓSTICO PRODUCCIÓN YUNTAS ===";
        $output[] = "Fecha: " . date('Y-m-d H:i:s');
        $output[] = "Entorno: " . config('app.env');
        $output[] = "URL: " . config('app.url');
        $output[] = "";

        // Verificar conexión BD
        try {
            DB::connection()->getPdo();
            $output[] = "✓ Conexión BD: OK";
        } catch (\Exception $e) {
            $output[] = "✗ Error BD: " . $e->getMessage();
        }

        // Verificar tablas
        $tables = ['permissions', 'roles', 'model_has_permissions', 'model_has_roles'];
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                $output[] = "✓ Tabla {$table}: {$count} registros";
            } catch (\Exception $e) {
                $output[] = "✗ Error tabla {$table}: " . $e->getMessage();
            }
        }

        // Verificar permisos
        $output[] = "";
        $output[] = "=== PERMISOS ===";
        $permissions = Permission::all();
        $output[] = "Total permisos: " . $permissions->count();
        
        $productPermissions = Permission::where('name', 'like', '%producto%')->get();
        $output[] = "Permisos de productos:";
        foreach ($productPermissions as $perm) {
            $output[] = "- {$perm->name}";
        }

        // Verificar roles
        $output[] = "";
        $output[] = "=== ROLES ===";
        $roles = Role::all();
        $output[] = "Total roles: " . $roles->count();
        foreach ($roles as $role) {
            $output[] = "- {$role->name}";
        }

        // Verificar usuarios
        $output[] = "";
        $output[] = "=== USUARIOS ===";
        $users = User::with(['roles', 'permissions'])->get();
        foreach ($users as $user) {
            $output[] = "Usuario: {$user->name} ({$user->email})";
            $output[] = "  Roles: " . $user->roles->pluck('name')->join(', ');
            $output[] = "  ¿Puede crear productos? " . ($user->can('crear-productos') ? 'SÍ' : 'NO');
            $output[] = "  ¿Tiene rol admin? " . ($user->hasRole('admin') ? 'SÍ' : 'NO');
            $output[] = "";
        }

        // Verificar rol admin
        $output[] = "=== ROL ADMIN ===";
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $output[] = "Rol admin encontrado";
            $output[] = "Permisos del admin:";
            foreach ($adminRole->permissions as $permission) {
                $output[] = "- {$permission->name}";
            }
        } else {
            $output[] = "✗ ROL ADMIN NO EXISTE";
        }

        return response()->json([
            'diagnostico' => implode("\n", $output)
        ]);
    }

    public function reparar()
    {
        $output = [];
        $output[] = "=== REPARANDO PERMISOS ===";

        try {
            // Limpiar cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $output[] = "✓ Cache limpiado";

            // Crear permisos
            $permissions = [
                'crear-productos', 'editar-productos', 'eliminar-productos', 'ver-productos',
                'crear-usuarios', 'editar-usuarios', 'eliminar-usuarios', 'ver-usuarios',
                'gestionar-roles', 'gestionar-permisos'
            ];

            foreach ($permissions as $perm) {
                Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
                $output[] = "✓ Permiso {$perm} verificado";
            }

            // Crear rol admin
            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $output[] = "✓ Rol admin verificado";

            // Asignar permisos al admin
            $allPermissions = Permission::whereIn('name', $permissions)->get();
            $adminRole->syncPermissions($allPermissions);
            $output[] = "✓ Permisos asignados al admin";

            // Asignar rol admin a todos los usuarios
            $users = User::all();
            foreach ($users as $user) {
                if (!$user->hasRole('admin')) {
                    $user->assignRole('admin');
                    $output[] = "✓ Rol admin asignado a {$user->name}";
                }
            }

            $output[] = "=== REPARACIÓN COMPLETADA ===";

        } catch (\Exception $e) {
            $output[] = "✗ Error: " . $e->getMessage();
        }

        return response()->json([
            'reparacion' => implode("\n", $output)
        ]);
    }
}
