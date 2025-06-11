<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="Gestión de permisos"
 * )
 */
class PermissionController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/permissions",
     *     summary="Obtener todos los permisos",
     *     tags={"Permissions"},
     *     @OA\Response(response=200, description="Lista de permisos")
     * )
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }
    /**
     * @OA\Get(
     *     path="/api/permissions/{id}",
     *     summary="Mostrar un permiso",
     *     tags={"Permissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permiso encontrado"),
     *     @OA\Response(response=404, description="Permiso no encontrado")
     * )
     */
    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        return response()->json($permission);
    }
    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     summary="Crear un nuevo permiso",
     *     tags={"Permissions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="edit articles")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Permiso creado"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:95',
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/permissions/{id}",
     *     summary="Actualizar un permiso",
     *     tags={"Permissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="edit users")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permiso actualizado"),
     *     @OA\Response(response=404, description="Permiso no encontrado"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:95|unique:permissions,name,' . $id,
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }
    /**
     * @OA\Delete(
     *     path="/api/permissions/{id}",
     *     summary="Eliminar un permiso",
     *     tags={"Permissions"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permiso eliminado"),
     *     @OA\Response(response=404, description="Permiso no encontrado")
     * )
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
