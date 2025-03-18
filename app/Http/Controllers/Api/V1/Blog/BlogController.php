<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Api\V1\BasicController;
use App\Models\Blog;
use App\Models\BloqueContenido;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Contains\HttpStatusCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Blogs",
 *     description="API para la gestión de blogs"
 * )
 */
class BlogController extends BasicController
{
    /**
     * Mostrar listado de blogs
     * 
     * @OA\Get(
     *     path="/api/v1/blogs",
     *     summary="Listar todos los blogs",
     *     description="Obtiene un listado de todos los blogs con sus bloques de contenido",
     *     operationId="getBlogsList",
     *     tags={"Blogs"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blogs obtenidos con éxito"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                     @OA\Property(property="descripcion", type="string", example="Descripción del blog"),
     *                     @OA\Property(property="imagen_principal", type="string", example="url/imagen.jpg"),
     *                     @OA\Property(property="estatus", type="string", example="publicado"),
     *                     @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                     @OA\Property(property="fecha_actualizacion", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="bloquesContenido",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="id_blog", type="integer", example=1),
     *                             @OA\Property(property="parrafo", type="string", example="Contenido del párrafo", nullable=true),
     *                             @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                             @OA\Property(property="descripcion_imagen", type="string", example="Descripción de la imagen", nullable=true),
     *                             @OA\Property(property="orden", type="integer", example=1),
     *                             @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                             @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $blogs = Blog::with('bloquesContenido')->get();
        
        return $this->successResponse($blogs, 'Blogs obtenidos con éxito');
    }

    /**
     * Crear nuevo blog
     * 
     * @OA\Post(
     *     path="/api/v1/blogs",
     *     summary="Crear un nuevo blog",
     *     description="Crea un nuevo blog con sus respectivos bloques de contenido",
     *     operationId="storeBlog",
     *     tags={"Blogs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo", "descripcion", "imagen_principal"},
     *             @OA\Property(property="titulo", type="string", example="Título del blog"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del blog"),
     *             @OA\Property(property="imagen_principal", type="string", example="url/imagen.jpg"),
     *             @OA\Property(property="estatus", type="string", example="borrador", enum={"borrador", "publicado"}),
     *             @OA\Property(
     *                 property="bloques_contenido",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="parrafo", type="string", example="Texto del párrafo"),
     *                     @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                     @OA\Property(property="descripcion_imagen", type="string", example="Descripción de la imagen", nullable=true),
     *                     @OA\Property(property="orden", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog creado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción del blog"),
     *                 @OA\Property(property="imagen_principal", type="string", example="url/imagen.jpg"),
     *                 @OA\Property(property="estatus", type="string", example="borrador"),
     *                 @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                 @OA\Property(property="fecha_actualizacion", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="bloquesContenido",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="id_blog", type="integer", example=1),
     *                         @OA\Property(property="parrafo", type="string", example="Contenido del párrafo", nullable=true),
     *                         @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                         @OA\Property(property="descripcion_imagen", type="string", example="Descripción de la imagen", nullable=true),
     *                         @OA\Property(property="orden", type="integer", example=1),
     *                         @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                         @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al crear el blog"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $blog = new Blog();
            $blog->titulo = $request->titulo;
            $blog->descripcion = $request->descripcion;
            $blog->imagen_principal = $request->imagen_principal;
            $blog->estatus = $request->estatus ?? 'borrador';
            $blog->fecha_creacion = Carbon::now();
            $blog->fecha_actualizacion = Carbon::now();
            $blog->save();

            // Guardar bloques de contenido si existen
            if ($request->has('bloques_contenido')) {
                foreach ($request->bloques_contenido as $key => $bloque) {
                    BloqueContenido::create([
                        'id_blog' => $blog->id_blog, // Usar id_blog en lugar de id
                        'parrafo' => $bloque['parrafo'] ?? null,
                        'imagen' => $bloque['imagen'] ?? null,
                        'descripcion_imagen' => $bloque['descripcion_imagen'] ?? null,
                        'orden' => $bloque['orden'] ?? ($key + 1),
                        'fecha_creacion' => Carbon::now(),
                        'fecha_actualizacion' => Carbon::now(),
                    ]);
                }
            }
            
            DB::commit();
            
            return $this->successResponse(
                Blog::with('bloquesContenido')->find($blog->id_blog), // Usar id_blog en lugar de id
                'Blog creado exitosamente',
                HttpStatusCode::CREATED
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->internalServerErrorResponse('Error al crear el blog: ' . $e->getMessage());
        }
    }

    /**
     * Obtener blog específico
     * 
     * @OA\Get(
     *     path="/api/v1/blogs/{blog}",
     *     summary="Obtener un blog específico",
     *     description="Obtiene los detalles de un blog específico por su ID",
     *     operationId="showBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         required=true,
     *         description="ID del blog",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog obtenido con éxito"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="Blog",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                     @OA\Property(property="descripcion", type="string", example="Descripción del blog"),
     *                     @OA\Property(property="imagen_principal", type="string", example="url/imagen.jpg"),
     *                     @OA\Property(property="estatus", type="string", example="publicado"),
     *                     @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                     @OA\Property(property="fecha_actualizacion", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="bloquesContenido",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="id_blog", type="integer", example=1),
     *                             @OA\Property(property="parrafo", type="string", example="Contenido del párrafo", nullable=true),
     *                             @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                             @OA\Property(property="descripcion_imagen", type="string", example="Descripción de la imagen", nullable=true),
     *                             @OA\Property(property="orden", type="integer", example=1),
     *                             @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                             @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Blog no encontrado"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function show(Blog $blog): JsonResponse
    {
        $blog->load('bloquesContenido');
        
        return $this->successResponse(['Blog' => $blog], 'Blog obtenido con éxito');
    }

    /**
     * Actualizar blog existente
     * 
     * @OA\Put(
     *     path="/api/v1/blogs/{blog}",
     *     summary="Actualizar un blog existente",
     *     description="Actualiza un blog específico y sus bloques de contenido",
     *     operationId="updateBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         required=true,
     *         description="ID del blog a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Título actualizado"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción actualizada"),
     *             @OA\Property(property="imagen_principal", type="string", example="url/nueva-imagen.jpg"),
     *             @OA\Property(property="estatus", type="string", example="publicado", enum={"borrador", "publicado"}),
     *             @OA\Property(
     *                 property="bloques_contenido",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id_bloque", type="integer", example=1, nullable=true),
     *                     @OA\Property(property="parrafo", type="string", example="Texto actualizado"),
     *                     @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                     @OA\Property(property="descripcion_imagen", type="string", example="Nueva descripción", nullable=true),
     *                     @OA\Property(property="orden", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog actualizado exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título actualizado"),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción actualizada"),
     *                 @OA\Property(property="imagen_principal", type="string", example="url/nueva-imagen.jpg"),
     *                 @OA\Property(property="estatus", type="string", example="publicado"),
     *                 @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                 @OA\Property(property="fecha_actualizacion", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="bloquesContenido",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="id_blog", type="integer", example=1),
     *                         @OA\Property(property="parrafo", type="string", example="Texto actualizado", nullable=true),
     *                         @OA\Property(property="imagen", type="string", example="url/imagen.jpg", nullable=true),
     *                         @OA\Property(property="descripcion_imagen", type="string", example="Nueva descripción", nullable=true),
     *                         @OA\Property(property="orden", type="integer", example=1),
     *                         @OA\Property(property="fecha_creacion", type="string", format="date-time"),
     *                         @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Blog no encontrado"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al actualizar el blog"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $blog->titulo = $request->titulo ?? $blog->titulo;
            $blog->descripcion = $request->descripcion ?? $blog->descripcion;
            $blog->imagen_principal = $request->imagen_principal ?? $blog->imagen_principal;
            $blog->estatus = $request->estatus ?? $blog->estatus;
            $blog->fecha_actualizacion = Carbon::now();
            $blog->save();

            // Actualizar bloques de contenido si existen
            if ($request->has('bloques_contenido')) {
                // Opcionalmente, se podría eliminar los bloques existentes
                // BloqueContenido::where('id_blog', $blog->id_blog)->delete();
                
                foreach ($request->bloques_contenido as $key => $bloqueData) {
                    if (isset($bloqueData['id_bloque'])) {
                        $bloque = BloqueContenido::find($bloqueData['id_bloque']);
                        if ($bloque && $bloque->id_blog == $blog->id_blog) { // Usar id_blog en lugar de id
                            $bloque->parrafo = $bloqueData['parrafo'] ?? $bloque->parrafo;
                            $bloque->imagen = $bloqueData['imagen'] ?? $bloque->imagen;
                            $bloque->descripcion_imagen = $bloqueData['descripcion_imagen'] ?? $bloque->descripcion_imagen;
                            $bloque->orden = $bloqueData['orden'] ?? $bloque->orden;
                            $bloque->fecha_actualizacion = Carbon::now();
                            $bloque->save();
                        }
                    } else {
                        // Crear nuevo bloque
                        BloqueContenido::create([
                            'id_blog' => $blog->id_blog, // Usar id_blog en lugar de id
                            'parrafo' => $bloqueData['parrafo'] ?? null,
                            'imagen' => $bloqueData['imagen'] ?? null,
                            'descripcion_imagen' => $bloqueData['descripcion_imagen'] ?? null,
                            'orden' => $bloqueData['orden'] ?? ($key + 1),
                            'fecha_creacion' => Carbon::now(),
                            'fecha_actualizacion' => Carbon::now(),
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return $this->successResponse(
                Blog::with('bloquesContenido')->find($blog->id_blog), // Usar id_blog en lugar de id
                'Blog actualizado exitosamente'
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->internalServerErrorResponse('Error al actualizar el blog: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar blog
     * 
     * @OA\Delete(
     *     path="/api/v1/blogs/{blog}",
     *     summary="Eliminar un blog",
     *     description="Elimina un blog específico y todos sus bloques de contenido asociados",
     *     operationId="destroyBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="blog",
     *         in="path",
     *         required=true,
     *         description="ID del blog a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Blog eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blog eliminado exitosamente"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Blog no encontrado"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al eliminar el blog"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function destroy(Blog $blog): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            // Eliminar bloques de contenido relacionados
            BloqueContenido::where('id_blog', $blog->id_blog)->delete(); // Usar id_blog en lugar de id
            
            // Eliminar el blog
            $blog->delete();
            
            DB::commit();
            
            return $this->noContentResponse('Blog eliminado exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->internalServerErrorResponse('Error al eliminar el blog: ' . $e->getMessage());
        }
    }
}
