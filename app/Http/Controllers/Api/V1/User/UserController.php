<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\PostUser\PostUser;
use App\Http\Requests\PostUser\PostUserUpdate;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode; // Importar HttpStatusCode
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Importar ModelNotFoundException

/**
 * @OA\Tag(
 *     name="Usuarios",
 *     description="API para gestión de usuarios"
 * )
 */
class UserController extends BasicController
{

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Listar todos los usuarios",
     *     description="Obtiene una lista de todos los usuarios registrados con sus roles",
     *     operationId="indexUsers",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuarios listados correctamente."),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                     @OA\Property(property="celular", type="string", example="1234567890"),
     *                     @OA\Property(property="roles", type="array",
     *                         @OA\Items(type="string"),
     *                         example={"USER"}
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al listar los usuarios"),
     *             @OA\Property(property="errors", type="null")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $users = User::with('roles')->get();

            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'celular' => $user->celular,
                    'roles' => $user->getRoleNames(),
                ];
            });

            $message = $users->isEmpty()
                ? "No hay usuarios disponibles."
                : "Usuarios listados correctamente.";

            return $this->successResponse($data, $message);
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema al listar los usuarios: " . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Obtener información del usuario autenticado",
     *     description="Obtiene los detalles del usuario actualmente autenticado, incluyendo sus roles y permisos",
     *     operationId="me",
     *     tags={"Usuarios"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Información del usuario obtenida exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="celular", type="string", example="1234567890"),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="name", type="string", example="admin")
     *                     )
     *                 ),
     *                 @OA\Property(property="permissions", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="name", type="string", example="eliminar-productos")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuario no autenticado"
     *     )
     * )
     */
    public function me(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return $this->unauthorizedResponse('Usuario no autenticado');
            }

            // Cargar roles y permisos
            $user->load(['roles', 'permissions']);

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'celular' => $user->celular,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name
                    ];
                }),
                'permissions' => $user->getAllPermissions()->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name
                    ];
                })
            ];

            return $this->successResponse($userData, 'Información del usuario obtenida exitosamente');
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse("Error al obtener información del usuario: " . $e->getMessage());
        }
    }



    /**
     * @OA\Get(
     * path="/api/v1/users/{id}",
     * summary="Obtener un usuario por ID",
     * description="Obtiene los detalles de un usuario específico, incluyendo sus roles.",
     * operationId="showUser",
     * tags={"Usuarios"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID del usuario a obtener",
     * required=true,
     * @OA\Schema(
     * type="integer",
     * format="int64"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Usuario encontrado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Usuario encontrado correctamente."),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", example="johndoe@example.com"),
     * @OA\Property(property="celular", type="string", example="1234567890"),
     * @OA\Property(property="fecha", type="string", example="1990-01-01", description="Fecha de nacimiento"),
     * @OA\Property(property="roles", type="array",
     * @OA\Items(type="string"),
     * example={"USER"}
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Usuario no encontrado",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="Recurso no encontrado"),
     * @OA\Property(property="errors", type="null")
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Error del servidor",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="Ocurrió un problema al obtener el usuario"),
     * @OA\Property(property="errors", type="null")
     * )
     * )
     * )
     */
    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'celular' => $user->celular,
                'roles' => $user->getRoleNames(),
            ];

            return $this->successResponse($data, 'Usuario encontrado correctamente.');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Recurso no encontrado');
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema al obtener el usuario: " . $e->getMessage());
        }
    }

        /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Crear un nuevo usuario",
     *     description="Crea un nuevo usuario y le asigna el rol USER",
     *     operationId="storeUser",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","celular","fecha"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="Nombre del usuario"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com", description="Correo electrónico"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="Contraseña"),
     *             @OA\Property(property="celular", type="string", example="980172891", description="Número de celular"),
     *             @OA\Property(property="fecha", type="string", format="date", example="1990-01-01", description="Fecha de nacimiento"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario creado correctamente"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al crear usuario"),
     *             @OA\Property(property="errors", type="null")
     *         )
     *     )
     * )
     */
    public function store(PostUser $request)
    {
        try {
            DB::beginTransaction();

            $userData = $request->validated();
            $userData['password'] = Hash::make($request['password']);

            $user = User::create($userData);
            $user->assignRole('user');

            DB::commit();

            return $this->successResponse($user, 'Usuario creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->internalServerErrorResponse('Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     summary="Actualizar usuario",
     *     description="Actualiza los datos de un usuario existente",
     *     operationId="updateUser",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario a actualizar",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe Updated", description="Nombre del usuario"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoeupdated@example.com", description="Correo electrónico"),
     *             @OA\Property(property="celular", type="string", example="0987654321", description="Número de celular"),
     *             @OA\Property(property="fecha", type="string", format="date", example="1990-01-01", description="Fecha de nacimiento"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario actualizado correctamente."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Recurso no encontrado"),
     *             @OA\Property(property="errors", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al actualizar al usuario"),
     *             @OA\Property(property="errors", type="null")
     *         )
     *     )
     * )
     */
    public function update(PostUserUpdate $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());

            $message = $user->wasChanged()
                ? "Usuario actualizado correctamente."
                : "No hubo cambios en los datos del usuario.";

            return $this->successResponse($user, $message);
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse("Ocurrió un problema al actualizar al usuario: " . $e->getMessage());
        }
    }

    
    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     summary="Eliminar usuario",
     *     description="Elimina un usuario por su ID",
     *     operationId="destroyUser",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario a eliminar",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado"),
     *             @OA\Property(property="errors", type="string", example="No query results for model [App\\Models\\User] 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema con la eliminación del usuario"),
     *             @OA\Property(property="errors", type="null")
     *         )
     *     )
     * )
     */


    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();
            return $this->successNoContentResponse('Usuario eliminado correctamente.');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return $this->errorResponse('Usuario no encontrado', HttpStatusCode::NOT_FOUND, $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Ocurrió un problema con la eliminación del usuario: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    

    /**
     * @OA\Post(
     *     path="/api/v1/users/{id}/role",
     *     summary="Asignar rol a un usuario",
     *     description="Asigna un rol específico a un usuario por su ID",
     *     operationId="assignRoleToUser",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario al que se le asignará el rol",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="ADMIN", description="Nombre del rol a asignar")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol asignado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rol asignado correctamente"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="Datos del usuario con el rol asignado",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="roles", type="array", @OA\Items(type="string"), example={"ADMIN"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="El rol es requerido y debe existir"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ocurrió un problema al asignar el rol")
     *         )
     *     )
     * )
     */
    public function assignRoleToUser(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);

        // Puedes limpiar roles anteriores si quieres solo un rol
        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Rol asignado correctamente', 'user' => $user]);
    }
}
