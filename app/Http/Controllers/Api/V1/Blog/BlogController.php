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
use App\Services\ImgurService;
use App\Models\Producto;
use App\Models\ImagenBlog;

/**
 * @OA\Tag(
 *     name="Blogs",
 *     description="API para la gestión de blogs"
 * )
 */
class BlogController extends BasicController
{

    protected ApiResponseService $apiResponse;
    protected $imgurService;

    public function __construct(ApiResponseService $apiResponse, ImgurService $imgurService)
    {
        $this->apiResponse = $apiResponse;
        $this->imgurService = $imgurService;
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
    private function guardarImagen($archivo)
    {
        Storage::putFileAs("public/imagenes", $archivo, $archivo->hashName());
        return "/storage/imagenes/" . $archivo->hashName();
    }

    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Validar que el producto existe
            $request->validate([
                'producto_id' => ['required', 'integer', 'exists:productos,id'],
            ]);

            // 🟡 Subir imagen principal
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido.");
                }

                $uploadedMainImageUrl = $this->guardarImagen($data['imagen_principal']);
                if (!$uploadedMainImageUrl) {
                    throw new \Exception("Falló la subida de la imagen principal.");
                }

                $data['imagen_principal'] = $uploadedMainImageUrl;
            }

            // Crear blog
            $blog = Blog::create(array_diff_key($data, array_flip([
                'imagenes',
                'video',
                'detalle'
            ])));

            // Relación: detalle
            if (!empty($data['titulo_blog']) || !empty($data['subtitulo_beneficio'])) {
                $blog->detalle()->create([
                    'id_blog' => $blog->id,
                    'titulo_blog' => $data['titulo_blog'] ?? null,
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? null,
                ]);
            }

            // Relación: video
            if (!empty($data['url_video']) || !empty($data['titulo_video'])) {
                $blog->video()->create([
                    'id_blog' => $blog->id,
                    'url_video' => $data['url_video'] ?? null,
                    'titulo_video' => $data['titulo_video'] ?? null,
                ]);
            }

            // Relación: imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                foreach ($data['imagenes'] as $index => $item) {
                    if (isset($item['imagen']) && $item['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                        $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!in_array($item['imagen']->getMimeType(), $validMimeTypes)) {
                            throw new \Exception("El archivo de imagen adicional en la posición $index no es válido.");
                        }

                        $uploadedImageUrl = $this->guardarImagen($item['imagen']);
                        if (!$uploadedImageUrl) {
                            throw new \Exception("Falló la subida de la imagen adicional en la posición $index.");
                        }

                        $blog->imagenes()->create([
                            'url_imagen' => $uploadedImageUrl,
                            'parrafo_imagen' => $item['parrafo'] ?? '',
                            'id_blog' => $blog->id,
                        ]);
                    } else {
                        throw new \Exception("Falta imagen válida en el índice $index.");
                    }
                }
            } else {
                throw new \Exception("Array de imágenes vacío o mal estructurado.");
            }

            DB::commit();

            return $this->apiResponse->successResponse($blog->fresh(), 'Blog creado con éxito.', HttpStatusCode::CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
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

        } catch(\Exception $e) {
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

        try {
            $blog = Blog::findOrFail($id);

            $producto = Producto::find($data['producto_id']);
            if (!$producto) {
                throw new \Exception("El producto con ID {$data['producto_id']} no existe.");
            }

            // Validar y subir imagen principal si viene en el request
            if (!empty($data['imagen_principal']) && $data['imagen_principal'] instanceof \Illuminate\Http\UploadedFile) {
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($data['imagen_principal']->getMimeType(), $validMimeTypes)) {
                    throw new \Exception("El archivo de imagen principal no es válido.");
                }
                $uploadedMainImageUrl = $this->imgurService->uploadImage($data['imagen_principal']);
                if (!$uploadedMainImageUrl) {
                    throw new \Exception("Falló la subida de la imagen principal.");
                }
                $data['imagen_principal'] = $uploadedMainImageUrl;
            }

            $blog->update([
                'producto_id' => $data['producto_id'],
                'titulo' => $data['titulo'],
                'link' => $data['link'],
                'parrafo' => $data['parrafo'],
                'descripcion' => $data['descripcion'],
                'imagen_principal' => $data['imagen_principal'] ?? $blog->imagen_principal,
                'updated_at' => now(),
            ]);

            // Imágenes adicionales
            if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
                $blog->imagenes()->delete(); // Eliminar imágenes anteriores

                $imagenes = collect($data['imagenes'])->map(fn($imagen) => [
                    'url_imagen' => $imagen['imagen'],
                    'parrafo_imagen' => $imagen['parrafo'],
                    'id_blog' => $blog->id,
                ])->toArray();

                ImagenBlog::insert($imagenes);
            }

            // Manejo detalle blog
            $detalle = $blog->detalle()->first();
            if ($detalle) {
                $detalle->update([
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            } else {
                $blog->detalle()->create([
                    'titulo_blog' => $data['titulo_blog'],
                    'subtitulo_beneficio' => $data['subtitulo_beneficio'],
                ]);
            }

            // Manejo video blog
            $video = $blog->video()->first();
            if ($video) {
                $video->update([
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
                ]);
            } else {
                $blog->video()->create([
                    'url_video' => $data['url_video'],
                    'titulo_video' => $data['titulo_video'],
                ]);
            }

            DB::commit();

            return $this->apiResponse->successResponse(null, 'Blog actualizado exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse('Error al actualizar el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
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
    public function destroy(int $id)
    {
       try {
            $blog = Blog::findOrFail($id);
            $blog->delete();

            return $this->apiResponse->successResponse(
                $blog,
                'Blog eliminado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
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
