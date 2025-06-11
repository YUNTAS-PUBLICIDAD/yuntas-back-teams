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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\TarjetaController;
use App\Http\Controllers\Api\CommendTarjetaController;
use App\Models\BlogBody;
use App\Services\ApiResponseService;
use App\Models\Producto;
use App\Models\ImagenBlog;
use App\Services\LocalStorageService;

/**
 * @OA\Tag(
 *     name="Blogs",
 *     description="API para la gestión de blogs"
 * )
 */
class BlogController extends BasicController
{

    protected $apiResponse;
    protected $storageService;

    public function __construct(
        ApiResponseService $apiResponse,
        LocalStorageService $storageService
    ) {
        $this->apiResponse = $apiResponse;
        $this->storageService = $storageService;
    }
    /**
     * @OA\Get(
     *     path="/api/blogs",
     *     summary="Obtener todos los blogs",
     *     description="Obtiene todos los blogs disponibles con su card asociada",
     *     operationId="getBlogs",
     *     tags={"Blogs"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Operación exitosa"),
     *             @OA\Property(property="data", type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_blog", type="integer", example=1),
     *                     @OA\Property(property="link", type="string", example="producto"),
     *                     @OA\Property(property="producto_id", type="integer", example=1),
     *                     @OA\Property(property="id_blog_head", type="integer", example=1),
     *                     @OA\Property(property="id_blog_body", type="integer", example=1),
     *                     @OA\Property(property="id_blog_footer", type="integer", example=1),
     *                     @OA\Property(property="fecha", type="string", example="2025-05-09")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $blog = Blog::with(['imagenes', 'video', 'detalle', 'producto'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'producto_id' => $blog->producto_id,
                    'titulo' => $blog->titulo,
                    'link' => $blog->link,
                    'parrafo' => $blog->parrafo,
                    'descripcion' => $blog->descripcion,
                    'imagenPrincipal' => $blog->imagen_principal,
                    'tituloBlog' => optional($blog->detalle)->titulo_blog,
                    'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                    'imagenesBlog' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'url' => $imagen->url_imagen,
                            'parrafo' => $imagen->parrafo_imagen,
                        ];
                    }),
                    'video_id   ' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                    'videoBlog' => optional($blog->video)->url_video,
                    'tituloVideoBlog' => optional($blog->video)->titulo_video,
                    'created_at' => $blog->created_at
                ];
            });
            return $this->apiResponse->successResponse(
                $showBlog,
                'Blogs obtenidos exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener los blogs: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }
    /**
     * @OA\Post(
     *     path="/api/blogs",
     *     summary="Crear un nuevo blog",
     *     description="Crea un nuevo blog con sus atributos",
     *     operationId="createBlog",
     *     tags={"Blogs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"producto_id","link","id_blog_head", "id_blog_body", "id_blog_footer", "fecha"},
     *             @OA\Property(property="producto_id", type="integer", example=1),
     *             @OA\Property(property="link", type="string", example="producto"),
     *             @OA\Property(property="id_blog_head", type="integer", example=1),
     *             @OA\Property(property="id_blog_body", type="integer", example=1),
     *             @OA\Property(property="id_blog_footer", type="integer", example=1),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-05-09")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Blog creada correctamente"),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */
    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {
            // Validar que el producto existe
            $request->validate([
                'producto_id' => ['required', 'integer', 'exists:productos,id'],
            ]);

            // 🟡 Validar y subir imagen principal si existe
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido. Tipo recibido: " . $data['imagen_principal']->getMimeType());
                }

                // Verificar el tamaño del archivo (ejemplo: máximo 10MB)
                if ($data['imagen_principal']->getSize() > 10485760) {
                    throw new \Exception("La imagen principal excede el tamaño máximo permitido de 10MB.");
                }

                // Subir imagen principal al almacenamiento local
                try {
                    $uploadedMainImageUrl = $this->storageService->uploadImage($data['imagen_principal']);

                    if (!$uploadedMainImageUrl) {
                        throw new \Exception("No se pudo guardar la imagen principal.");
                    }

                    $data['imagen_principal'] = $uploadedMainImageUrl;

                    \Log::info('Imagen principal guardada exitosamente', [
                        'url' => $uploadedMainImageUrl
                    ]);
                } catch (\Exception $storageException) {
                    \Log::error('Error al guardar imagen', [
                        'error' => $storageException->getMessage()
                    ]);
                    throw new \Exception("Error al guardar la imagen: " . $storageException->getMessage());
                }
            }

            // Crear el blog (excluyendo relaciones)
            $blog = Blog::create(array_diff_key($data, array_flip([
                'imagenes',
                'video',
                'detalle',
                'titulo_blog',
                'subtitulo_beneficio',
                'url_video',
                'titulo_video'
            ])));

            // Relación: detalle del blog
            if (!empty($data['titulo_blog']) || !empty($data['subtitulo_beneficio'])) {
                $blog->detalle()->create([
                    'titulo_blog' => $data['titulo_blog'] ?? null,
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? null,
                ]);
            }

            // Relación: video
            if (!empty($data['url_video']) || !empty($data['titulo_video'])) {
                $blog->video()->create([
                    'url_video' => $data['url_video'] ?? null,
                    'titulo_video' => $data['titulo_video'] ?? null,
                ]);
            }

            // Relación: imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $index => $item) {
                    if (!isset($item['imagen']) || !($item['imagen'] instanceof \Illuminate\Http\UploadedFile)) {
                        throw new \Exception("Falta imagen válida en el índice $index.");
                    }

                    $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!in_array($item['imagen']->getMimeType(), $validMimeTypes)) {
                        throw new \Exception("El archivo de imagen adicional en la posición $index no es válido. Tipo: " . $item['imagen']->getMimeType());
                    }

                    // Verificar tamaño
                    if ($item['imagen']->getSize() > 10485760) {
                        throw new \Exception("La imagen en la posición $index excede el tamaño máximo permitido de 10MB.");
                    }

                    try {
                        $uploadedImageUrl = $this->storageService->uploadImage($item['imagen']);

                        if (!$uploadedImageUrl) {
                            throw new \Exception("No se pudo guardar la imagen.");
                        }

                        $blog->imagenes()->create([
                            'url_imagen' => $uploadedImageUrl,
                            'parrafo_imagen' => $item['parrafo'] ?? '',
                        ]);

                        \Log::info('Imagen adicional guardada', [
                            'index' => $index,
                            'url' => $uploadedImageUrl
                        ]);
                    } catch (\Exception $storageException) {
                        throw new \Exception("Error al guardar imagen $index: " . $storageException->getMessage());
                    }
                }
            }

            DB::commit();

            // Cargar todas las relaciones antes de devolver
            $blog->load(['detalle', 'video', 'imagenes']);

            return $this->apiResponse->successResponse(
                $blog,
                'Blog creado con éxito.',
                HttpStatusCode::CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Blog creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->apiResponse->errorResponse(
                'Error al crear el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }
    /**
     * @OA\Get(
     *     path="/api/blogs/{id}",
     *     summary="Obtener un blog específico",
     *     description="Obtiene los detalles de un blog específico usando su ID",
     *     operationId="showBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Blog encontrado"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id_blog", type="integer", example=1),
     *                 @OA\Property(property="link", type="string", example="producto"),
     *                 @OA\Property(property="producto_id", type="integer", example=1),
     *                 @OA\Property(property="id_blog_head", type="integer", example=1),
     *                 @OA\Property(property="id_blog_body", type="integer", example=1),
     *                 @OA\Property(property="id_blog_footer", type="integer", example=1),
     *                 @OA\Property(property="fecha", type="string", example="2025-05-09")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Blog no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'link' => $blog->link,
                'parrafo' => $blog->parrafo,
                'descripcion' => $blog->descripcion,
                'imagenPrincipal' => $blog->imagen_principal,
                'tituloBlog' => optional($blog->detalle)->titulo_blog,
                'subTituloBlog' => optional($blog->detalle)->subtitulo_beneficio,
                'imagenesBlog' => $blog->imagenes->pluck('url_imagen'),
                'parrafoImagenesBlog' => $blog->imagenes->pluck('parrafo_imagen'),
                'video_id' => $this->obtenerIdVideoYoutube(optional($blog->video)->url_video),
                'videoBlog' => optional($blog->video)->url_video,
                'tituloVideoBlog' => optional($blog->video)->titulo_video,
                'created_at' => $blog->created_at,
            ];

            return $this->apiResponse->successResponse($showBlog, 'Blog obtenido exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/blogs/{id}",
     *     summary="Actualizar un blog existente",
     *     description="Actualiza los detalles de un blog específico",
     *     operationId="updateBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"producto_id", "link","id_blog_head", "id_blog_body", "id_blog_footer", "fecha"},
     *             @OA\Property(property="producto_id", type="integer", example=1),
     *             @OA\Property(property="link", type="string", example="producto"),
     *             @OA\Property(property="id_blog_head", type="integer", example=1),
     *             @OA\Property(property="id_blog_body", type="integer", example=1),
     *             @OA\Property(property="id_blog_footer", type="integer", example=1),
     *             @OA\Property(property="fecha", type="string", format="date", example="2025-05-09")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Blog actualizado"),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Blog no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */


    public function update(UpdateBlogRequest $request, $id)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {
            // Buscar el blog con sus relaciones
            $blog = Blog::with(['detalle', 'video', 'imagenes'])->findOrFail($id);

            // Validar que el producto existe si se está actualizando
            if (isset($data['producto_id'])) {
                $request->validate([
                    'producto_id' => ['required', 'integer', 'exists:productos,id'],
                ]);
            }

            // 🟡 Validar y subir imagen principal si se envió una nueva
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido. Tipo recibido: " . $data['imagen_principal']->getMimeType());
                }

                // Verificar el tamaño del archivo (ejemplo: máximo 10MB)
                if ($data['imagen_principal']->getSize() > 10485760) {
                    throw new \Exception("La imagen principal excede el tamaño máximo permitido de 10MB.");
                }

                // Subir nueva imagen principal al almacenamiento local
                try {
                    // Eliminar imagen anterior si existe y es del almacenamiento local
                    if ($blog->imagen_principal && str_contains($blog->imagen_principal, '/storage/')) {
                        $this->storageService->deleteImage($blog->imagen_principal);
                        \Log::info('Imagen principal anterior eliminada', [
                            'url' => $blog->imagen_principal
                        ]);
                    }

                    $uploadedMainImageUrl = $this->storageService->uploadImage($data['imagen_principal']);

                    if (!$uploadedMainImageUrl) {
                        throw new \Exception("No se pudo guardar la nueva imagen principal.");
                    }

                    $data['imagen_principal'] = $uploadedMainImageUrl;

                    \Log::info('Nueva imagen principal guardada exitosamente', [
                        'url' => $uploadedMainImageUrl
                    ]);
                } catch (\Exception $storageException) {
                    \Log::error('Error al guardar nueva imagen principal', [
                        'error' => $storageException->getMessage()
                    ]);
                    throw new \Exception("Error al guardar la imagen: " . $storageException->getMessage());
                }
            }

            // Actualizar el blog (excluyendo relaciones)
            $blog->update(array_diff_key($data, array_flip([
                'imagenes',
                'video',
                'detalle',
                'titulo_blog',
                'subtitulo_beneficio',
                'url_video',
                'titulo_video',
                'imagenes_a_eliminar'
            ])));

            // Actualizar o crear relación: detalle del blog
            if (isset($data['titulo_blog']) || isset($data['subtitulo_beneficio'])) {
                if ($blog->detalle) {
                    // Actualizar detalle existente
                    $blog->detalle->update([
                        'titulo_blog' => $data['titulo_blog'] ?? $blog->detalle->titulo_blog,
                        'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? $blog->detalle->subtitulo_beneficio,
                    ]);
                } else {
                    // Crear nuevo detalle si no existe
                    $blog->detalle()->create([
                        'titulo_blog' => $data['titulo_blog'] ?? null,
                        'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? null,
                    ]);
                }
            }

            // Actualizar o crear relación: video
            if (isset($data['url_video']) || isset($data['titulo_video'])) {
                if ($blog->video) {
                    // Actualizar video existente
                    $blog->video->update([
                        'url_video' => $data['url_video'] ?? $blog->video->url_video,
                        'titulo_video' => $data['titulo_video'] ?? $blog->video->titulo_video,
                    ]);
                } else {
                    // Crear nuevo video si no existe
                    $blog->video()->create([
                        'url_video' => $data['url_video'] ?? null,
                        'titulo_video' => $data['titulo_video'] ?? null,
                    ]);
                }
            }

            // Eliminar imágenes marcadas para eliminación
            if (!empty($data['imagenes_a_eliminar']) && is_array($data['imagenes_a_eliminar'])) {
                $imagenesAEliminar = $blog->imagenes()->whereIn('id', $data['imagenes_a_eliminar'])->get();

                foreach ($imagenesAEliminar as $imagen) {
                    // Eliminar archivo físico si es del almacenamiento local
                    if ($imagen->url_imagen && str_contains($imagen->url_imagen, '/storage/')) {
                        $this->storageService->deleteImage($imagen->url_imagen);
                        \Log::info('Imagen adicional eliminada', [
                            'id' => $imagen->id,
                            'url' => $imagen->url_imagen
                        ]);
                    }
                    // Eliminar registro de la base de datos
                    $imagen->delete();
                }
            }

            // Actualizar o agregar imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $index => $item) {
                    // Si solo se actualiza el párrafo de una imagen existente
                    if (isset($item['id']) && !isset($item['imagen'])) {
                        $imagenExistente = $blog->imagenes()->find($item['id']);
                        if ($imagenExistente && isset($item['parrafo'])) {
                            $imagenExistente->update([
                                'parrafo_imagen' => $item['parrafo']
                            ]);
                            \Log::info('Párrafo de imagen actualizado', [
                                'id' => $item['id']
                            ]);
                        }
                    }
                    // Si se sube una nueva imagen (con o sin ID)
                    else if (isset($item['imagen']) && $item['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                        $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!in_array($item['imagen']->getMimeType(), $validMimeTypes)) {
                            throw new \Exception("El archivo de imagen adicional en la posición $index no es válido. Tipo: " . $item['imagen']->getMimeType());
                        }

                        // Verificar tamaño
                        if ($item['imagen']->getSize() > 10485760) {
                            throw new \Exception("La imagen en la posición $index excede el tamaño máximo permitido de 10MB.");
                        }

                        try {
                            $uploadedImageUrl = $this->storageService->uploadImage($item['imagen']);

                            if (!$uploadedImageUrl) {
                                throw new \Exception("No se pudo guardar la imagen.");
                            }

                            // Si tiene ID, es un reemplazo de imagen existente
                            if (isset($item['id'])) {
                                $imagenExistente = $blog->imagenes()->find($item['id']);
                                if ($imagenExistente) {
                                    // Eliminar imagen anterior
                                    if ($imagenExistente->url_imagen && str_contains($imagenExistente->url_imagen, '/storage/')) {
                                        $this->storageService->deleteImage($imagenExistente->url_imagen);
                                    }

                                    // Actualizar con nueva imagen
                                    $imagenExistente->update([
                                        'url_imagen' => $uploadedImageUrl,
                                        'parrafo_imagen' => $item['parrafo'] ?? $imagenExistente->parrafo_imagen,
                                    ]);

                                    \Log::info('Imagen adicional reemplazada', [
                                        'id' => $item['id'],
                                        'nueva_url' => $uploadedImageUrl
                                    ]);
                                }
                            } else {
                                // Es una imagen completamente nueva
                                $blog->imagenes()->create([
                                    'url_imagen' => $uploadedImageUrl,
                                    'parrafo_imagen' => $item['parrafo'] ?? '',
                                ]);

                                \Log::info('Nueva imagen adicional agregada', [
                                    'index' => $index,
                                    'url' => $uploadedImageUrl
                                ]);
                            }
                        } catch (\Exception $storageException) {
                            throw new \Exception("Error al guardar imagen $index: " . $storageException->getMessage());
                        }
                    }
                }
            }

            DB::commit();

            // Cargar todas las relaciones actualizadas antes de devolver
            $blog->load(['detalle', 'video', 'imagenes']);

            return $this->apiResponse->successResponse(
                $blog,
                'Blog actualizado con éxito.',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Blog update failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'blog_id' => $id
            ]);

            return $this->apiResponse->errorResponse(
                'Error al actualizar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/blogs/{id}",
     *     summary="Eliminar un blog",
     *     description="Elimina un blog específico usando su ID",
     *     operationId="destroyBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del blog a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog eliminado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Blog eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Blog no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $blog = Blog::with(['imagenes'])->findOrFail($id);

            // Eliminar imagen principal si existe
            if ($blog->imagen_principal && str_contains($blog->imagen_principal, '/storage/')) {
                $this->storageService->deleteImage($blog->imagen_principal);
            }

            // Eliminar imágenes adicionales
            foreach ($blog->imagenes as $imagen) {
                if ($imagen->url_imagen && str_contains($imagen->url_imagen, '/storage/')) {
                    $this->storageService->deleteImage($imagen->url_imagen);
                }
            }

            // Eliminar el blog (las relaciones se eliminarán por cascade)
            $blog->delete();

            DB::commit();

            return $this->apiResponse->successResponse(
                null,
                'Blog eliminado con éxito.',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->apiResponse->errorResponse(
                'Error al eliminar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/blogs/link/{link}",
     *     summary="Obtener un blog por su campo link",
     *     description="Retorna un blog específico buscando por el valor único del campo 'link'.",
     *     operationId="getBlogByLink",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="link",
     *         in="path",
     *         required=true,
     *         description="Valor del campo 'link' para buscar el blog",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog encontrado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id_blog", type="integer", example=123),
     *             @OA\Property(property="link", type="string", example="mi-link-unico"),
     *             @OA\Property(property="producto_id", type="integer", example=10),
     *             @OA\Property(property="id_blog_head", type="integer", example=1),
     *             @OA\Property(property="id_blog_body", type="integer", example=1),
     *             @OA\Property(property="id_blog_footer", type="integer", example=1),
     *             @OA\Property(property="fecha", type="string", example="2025-05-09")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Blog no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Error en el servidor")
     *         )
     *     )
     * )
     */

    public function getByLink($link)
    {
        $blog = Blog::where('link', $link)->first();
        if (!$blog) {
            return response()->json(['message' => 'Blog no encontrado'], 404);
        }
        return response()->json($blog);
    }

    private function obtenerIdVideoYoutube($url)
    {
        $pattern = '%(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([^\s&?]+)%';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
