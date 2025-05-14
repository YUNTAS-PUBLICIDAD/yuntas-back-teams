<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Obtener todos los permisos
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    // Obtener un permiso específico
    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        return response()->json($permission);
    }

    // Crear un nuevo permiso
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
        ]);

        $permission = Permission::create($validated);

        return response()->json($permission, 201);
    }

    // Actualizar un permiso
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }

    // Eliminar un permiso
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
