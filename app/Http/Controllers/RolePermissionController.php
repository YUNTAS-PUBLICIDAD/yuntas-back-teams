<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * @OA\Tag(
 *     name="RolePermissions",
 *     description="Gestión de permisos asignados a roles"
 * )
 */
class RolePermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles/{roleId}/permissions",
     *     summary="Listar permisos asignados a un rol",
     *     tags={"RolePermissions"},
     *     @OA\Parameter(name="roleId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permisos del rol")
     * )
     */
    public function index($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json($role->permissions);
    }
    /**
     * @OA\Post(
     *     path="/api/roles/{roleId}/permissions",
     *     summary="Asignar permisos a un rol",
     *     tags={"RolePermissions"},
     *     @OA\Parameter(name="roleId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="permissions",
     *                 type="array",
     *                 @OA\Items(type="string", example="edit users")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permisos asignados correctamente"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/roles/{roleId}/permissions/{permissionId}",
     *     summary="Eliminar permiso de un rol",
     *     tags={"RolePermissions"},
     *     @OA\Parameter(name="roleId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="permissionId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permiso eliminado del rol correctamente")
     * )
     */
    public function destroy($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        $role->revokePermissionTo($permission);

        return response()->json(['message' => 'Permiso eliminado del rol correctamente']);
    }
}
