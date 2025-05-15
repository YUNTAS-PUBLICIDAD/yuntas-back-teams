<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Listar permisos de un rol
    public function index($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json($role->permissions);
    }

    // Asignar/sincronizar permisos a un rol
    public function store(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permissions);

        return response()->json(['message' => 'Permisos asignados correctamente', 'permissions' => $role->permissions]);
    }

    // Eliminar un permiso específico de un rol
    public function destroy($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        $role->revokePermissionTo($permission);

        return response()->json(['message' => 'Permiso eliminado del rol correctamente']);
    }
}
