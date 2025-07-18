<?php

namespace App\Http\Controllers\Api\V1\Blog;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Http\Requests\PostBlog\UpdateBlog;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog;
use App\Http\Contains\HttpStatusCode;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    protected ApiResponseService $apiResponse;

    public function __construct(ApiResponseService $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }
=======
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
use App\Models\BlogBody;
use App\Services\ApiResponseService;
use App\Models\Producto;
use App\Models\ImagenBlog;
use App\Services\LocalStorageService;


class BlogController extends BasicController
{

    protected $apiResponse;
    protected $storageService;

    private const MAX_IMAGE_SIZE = 10485760; // 10MB
    private const VALID_MIME_TYPES = ['image/jpeg', 'image/png', 'image/jpg'];

    public function __construct(
        ApiResponseService $apiResponse,
        LocalStorageService $storageService
    ) {
        $this->apiResponse = $apiResponse;
        $this->storageService = $storageService;
    }

>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b
    public function index()
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'titulo' => $blog->titulo,
                    'producto_id' => $blog->producto_id,
                    'link' => $blog->link,
                    'subtitulo1' => $blog->subtitulo1,
                    'subtitulo2' => $blog->subtitulo2,
                    'subtitulo3' => $blog->subtitulo3,
                    'video_id   ' => $this->obtenerIdVideoYoutube($blog->video_url),
                    'video_url' => $blog->video_url,
                    'video_titulo' => $blog->video_titulo,
                    'imagen_principal' => $blog->imagen_principal,
                    'imagenes' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'ruta_imagen' => $imagen->ruta_imagen,
                            'texto_alt' => $imagen->texto_alt,
                        ];
                    }),
                    'parrafos' => $blog->parrafos->map(function ($parrafo) {
                        return [
                            'parrafo' => $parrafo->parrafo,
                        ];
                    }),
                    'created_at' => $blog->created_at,
                    'updated_at' => $blog->updated_at
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
     * Crear un nuevo blog
     * 
     * @OA\Post(
     *     path="/api/v1/blogs",
     *     summary="Crear un nuevo blog",
     *     description="Almacena un nuevo blog y retorna los datos creados",
     *     operationId="storeBlog",
     *     tags={"Blogs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "producto_id",  
     *                     "titulo", 
     *                     "link", 
     *                     "parrafo", 
     *                     "descripcion", 
     *                     "imagen_principal", 
     *                     "titulo_blog", 
     *                     "subtitulo_beneficio", 
     *                     "url_video", 
     *                     "titulo_video"
     *                 },
     *                 @OA\Property(
     *                     property="titulo",
     *                     type="string",
     *                     example="Título del blog"
     *                 ),
     *                  @OA\Property(
     *                     property="link",
     *                     type="string",
     *                     example="Link a blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="parrafo",
     *                     type="string",
     *                     example="Contenido del blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="descripcion",
     *                     type="string",
     *                     example="Descripción del blog..."
     *                 ),
     *                 @OA\Property(
     *                     property="imagen_principal",
     *                     type="string",
     *                     format="binary",
     *                     description="Archivo de imagen principal del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="titulo_blog",
     *                     type="string",
     *                     example="Título del detalle del blog"
     *                 ),
     *                 @OA\Property(
     *                     property="subtitulo_beneficio",
     *                     type="string",
     *                     example="Subtítulo de beneficios"
     *                 ),
     *                 @OA\Property(
     *                     property="url_video",
     *                     type="string",
     *                     example="https://example.com/video.mp4"
     *                 ),
     *                 @OA\Property(
     *                     property="titulo_video",
     *                     type="string",
     *                     example="Título del video"
     *                 ),
     *                 @OA\Property(
     *                     property="imagenes",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(
     *                             property="imagen",
     *                             type="string",
     *                             example="https://example.com/imagen-adicional.jpg",
     *                             format="binary",
     *                             description="Archivo de imagen adicional"
     *                         ),
     *                         @OA\Property(
     *                             property="parrafo",
     *                             type="string",
     *                             description="Descripción de la imagen adicional",
     *                             example="Parrafo de la imagen adicional"
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Blog creado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

        private function guardarImagen($archivo)
        {
            $nombre = uniqid() . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs("imagenes", $nombre, "public");
            return "/storage/imagenes/" . $nombre;
        }


   public function store(PostStoreBlog $request)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();

        try {
            if (!$request->hasFile('imagen_principal')) {
                throw new \Exception('No se recibió imagen_principal como archivo');
            }

            $imagenPrincipal = $request->file("imagen_principal");
            $rutaImagenPrincipal = $this->guardarImagen($imagenPrincipal);

            $blog = Blog::create([
                "titulo" => $datosValidados["titulo"],
                "producto_id" => $datosValidados["producto_id"],
                "link" => $datosValidados["link"],
                "subtitulo1" => $datosValidados["subtitulo1"],
                "subtitulo2" => $datosValidados["subtitulo2"],
                "subtitulo3" => $datosValidados["subtitulo3"],
                "video_url" => $datosValidados["video_url"],
                "video_titulo" => $datosValidados["video_titulo"],
                "imagen_principal" => $rutaImagenPrincipal,
            ]);

            // Guardar imágenes
            $imagenes = $request->file("imagenes", []);
            $altTexts = $datosValidados["text_alt"] ?? [];

            foreach ($imagenes as $i => $imagen) {
                $ruta = $this->guardarImagen($imagen);

                $blog->imagenes()->create([
                    "ruta_imagen" => $ruta,
                    "text_alt" => $altTexts[$i] ?? null
                ]);
            }
            foreach($datosValidados["parrafos"] as $item) {
                $blog->parrafos()->createMany([
                    ["parrafo" =>$item]
                ]);
            }

            DB::commit();
            return $this->apiResponse->successResponse($blog->fresh(), 'Blog creado con éxito.', HttpStatusCode::CREATED);
<<<<<<< HEAD
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al crear el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Mostrar un blog específico
     * 
     * @OA\Get(
     *     path="/api/v1/blogs/{id}",
     *     summary="Muestra un blog específico",
     *     description="Retorna los datos de un blog según su ID",
     *     operationId="showBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="producto_id", type="integer", example=1),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="link", type="string", example="Link a blog..."),
     *                 @OA\Property(property="parrafo", type="string", example="Contenido del blog..."),
     *                 @OA\Property(property="descripcion", type="string", example="Descripcion del blog..."),
     *                 @OA\Property(property="imagen_principal", type="string", example="https://example.com/imagen-principal.jpg"),
     *                 @OA\Property(property="titulo_blog", type="string", example="Título del detalle del blog"),
     *                 @OA\Property(property="subtitulo_beneficio", type="string", example="Subtítulo de beneficios"),
     *                 @OA\Property(property="imagenes", type="array", 
     *                     @OA\Items(
     *                         @OA\Property(property="url_imagen", type="string", example="https://example.com/imagen1.jpg"),
     *                         @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen")
     *                     )
     *                 ),
     *                 @OA\Property(property="url_video", type="string", example="https://example.com/video.mp4"),
     *                 @OA\Property(property="titulo_video", type="string", example="Título del video"),
     *                 @OA\Property(property="created_at", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="Blog encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto'])
                        ->findOrFail($id);
=======
    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'detalle', 'video'])->findOrFail($id);
>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'producto_id' => $blog->producto_id,
                'link' => $blog->link,
<<<<<<< HEAD
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'subtitulo3' => $blog->subtitulo3,
                'video_id' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'imagen_principal' => $blog->imagen_principal,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->text_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
=======
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
>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

<<<<<<< HEAD
            return $this->apiResponse->successResponse(
                $showBlog,
                'Blog obtenido exitosamente',
                HttpStatusCode::OK
            );

        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }


    /**
     * Mostrar un blog por su link
     * 
     * @OA\Get(
     *     path="/api/v1/blogs/link/{link}",
     *     summary="Muestra un blog por su link",
     *     description="Retorna los datos de un blog, incluyendo detalles, imágenes y video, según su campo link",
     *     operationId="showBlogByLink",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="link",
     *         in="path",
     *         description="Link único del blog",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="producto_id", type="integer", example=5),
     *                 @OA\Property(property="titulo", type="string", example="Título del blog"),
     *                 @OA\Property(property="link", type="string", example="mi-blog-unico"),
     *                 @OA\Property(property="parrafo", type="string", example="Contenido introductorio del blog."),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción completa del blog."),
     *                 @OA\Property(property="imagenPrincipal", type="string", example="https://example.com/imagen-principal.jpg"),
     *                 @OA\Property(property="tituloBlog", type="string", example="Título del detalle del blog"),
     *                 @OA\Property(property="subTituloBlog", type="string", example="Subtítulo de beneficios"),
     *                 @OA\Property(property="imagenesBlog", type="array", 
     *                     @OA\Items(type="string", example="https://example.com/imagen1.jpg")
     *                 ),
     *                 @OA\Property(property="parrafoImagenesBlog", type="array", 
     *                     @OA\Items(type="string", example="Texto descriptivo de la imagen")
     *                 ),
     *                 @OA\Property(property="video_id", type="string", example="dQw4w9WgXcQ"),
     *                 @OA\Property(property="videoBlog", type="string", example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"),
     *                 @OA\Property(property="tituloVideoBlog", type="string", example="Título del video"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *             ),
     *             @OA\Property(property="message", type="string", example="Blog obtenido exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener el blog"
     *     )
     * )
     */

    public function showLink(string $link)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto'])
                ->where('link', $link)
                ->firstOrFail();

            $showBlog = [
                'id' => $blog->id,
                'titulo' => $blog->titulo,
                'producto_id' => $blog->producto_id,
                'link' => $blog->link,
                'subtitulo1' => $blog->subtitulo1,
                'subtitulo2' => $blog->subtitulo2,
                'subtitulo3' => $blog->subtitulo3,
                'video_id   ' => $this->obtenerIdVideoYoutube($blog->video_url),
                'video_url' => $blog->video_url,
                'video_titulo' => $blog->video_titulo,
                'imagen_principal' => $blog->imagen_principal,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => $imagen->ruta_imagen,
                        'texto_alt' => $imagen->texto_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

            return $this->apiResponse->successResponse(
                $showBlog,
                'Blog obtenido exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Actualizar un blog específico
     * 
     * @OA\Post(
     *     path="/api/v1/blogs/{id}",
     *     summary="Actualiza un blog específico",
     *     description="Actualiza los datos de un blog existente según su ID",
     *     operationId="updateBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Título actualizado del blog"),
     *             @OA\Property(property="link", type="string", example="Link a blog..."),
     *             @OA\Property(property="parrafo", type="string", example="Contenido actualizado del blog..."),
     *             @OA\Property(property="descripcion", type="string", example="Descripcion actualizado del blog..."),
     *             @OA\Property(property="imagen_principal", type="string", example="https://example.com/nueva-imagen.jpg"),
     *             @OA\Property(property="titulo_blog", type="string", example="Título del detalle actualizado"),
     *             @OA\Property(property="subtitulo_beneficio", type="string", example="Subtítulo de beneficios actualizado"),
     *             @OA\Property(property="imagenes", type="array", 
     *                 @OA\Items(
     *                     @OA\Property(property="url_imagen", type="string", example="https://example.com/nueva-imagen1.jpg"),
     *                     @OA\Property(property="parrafo_imagen", type="string", example="Descripción de la imagen actualizada")
     *                 )
     *             ),
     *             @OA\Property(property="url_video", type="string", example="https://example.com/nuevo-video.mp4"),
     *             @OA\Property(property="titulo_video", type="string", example="Título del video actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Blog actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

    public function update(UpdateBlog $request, $id)
    {
        $datosValidados = $request->validated();
        DB::beginTransaction();
        $blog = Blog::findOrFail($id);

        try {
            $nuevaRutaImagenPrincipal = $blog->imagen_principal;

            if ($request->hasFile('imagen_principal')) {
                if ($blog->imagen_principal) {
                    $rutaAnterior = str_replace('/storage/', '', $blog->imagen_principal);
                    Storage::disk('public')->delete($rutaAnterior);
                }

                $nuevaRutaImagenPrincipal = $this->guardarImagen($request->file('imagen_principal'));
            }
            // Actualizar datos principales del blog
            $blog->update([
                "titulo" => $datosValidados["titulo"],
                "producto_id" => $datosValidados["producto_id"],
                "link" => $datosValidados["link"],
                "subtitulo1" => $datosValidados["subtitulo1"],
                "subtitulo2" => $datosValidados["subtitulo2"],
                "subtitulo3" => $datosValidados["subtitulo3"],
                "video_url" => $datosValidados["video_url"],
                "video_titulo" => $datosValidados["video_titulo"],
                "imagen_principal" => $nuevaRutaImagenPrincipal,
            ]);

            // Eliminar imágenes anteriores del disco y base de datos
            $rutasImagenes = [];
            foreach($blog->imagenes as $item) {
                array_push($rutasImagenes, str_replace($item["ruta_imagen"], "storage/", ""));  
            }
            Storage::delete($rutasImagenes);
            $blog->imagenes()->delete();
            $blog->parrafos()->delete();

            // Guardar nuevas imágenes
            $imagenes = $request->file("imagenes", []);
            $altTexts = $datosValidados["text_alt"] ?? [];

            foreach ($imagenes as $i => $imagen) {
                $ruta = $this->guardarImagen($imagen);

                $blog->imagenes()->create([
                    "ruta_imagen" => $ruta,
                    "text_alt" => $altTexts[$i] ?? null
                ]);
            }

            // Guardar nuevos párrafos
            $parrafos = $datosValidados["parrafos"];
            foreach ($parrafos as $texto) {
                $blog->parrafos()->create([
                    "parrafo" => $texto
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
     * Eliminar un blog específico
     * 
     * @OA\Delete(
     *     path="/api/v1/blogs/{id}",
     *     summary="Elimina un blog específico",
     *     description="Elimina un blog existente según su ID",
     *     operationId="destroyBlog",
     *     tags={"Blogs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del blog a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Blog eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail($id);

            $rutasImagenes = [];
            foreach ($blog->imagenes as $imagen) {
                $relativePath = str_replace('storage/', '', $imagen->ruta_imagen);
                array_push($rutasImagenes, $relativePath);
            }

            $blog->imagenes()->delete();
            $blog->parrafos()->delete();

            if (!empty($rutasImagenes)) {
                Storage::delete($rutasImagenes);
            }

            $blog->delete();

            DB::commit();
=======
            return $this->apiResponse->successResponse($showBlog, 'Blog obtenido exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse('Error al obtener el blog: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
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
>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b

            return $this->apiResponse->successResponse(
                200,
                'Blog eliminado con éxito.',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
<<<<<<< HEAD
=======

>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b
            return $this->apiResponse->errorResponse(
                'Error al eliminar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }
<<<<<<< HEAD

=======
    public function getByLink($link)
    {
        $blog = Blog::where('link', $link)->first();
        if (!$blog) {
            return response()->json(['message' => 'Blog no encontrado'], 404);
        }
        return response()->json($blog);
    }
>>>>>>> 64cff5f58f56ecf62c12e61853792701966b524b
    private function obtenerIdVideoYoutube($url)
    {
        $pattern = '%(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([^\s&?]+)%';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        \Log::info('Datos RAW recibidos:', $request->all());
        
        $data = $request->validated();

        \Log::info('Datos validados:', $data);
        \Log::info('¿Tiene producto_id?', ['tiene_producto_id' => isset($data['producto_id'])]);


        DB::beginTransaction();

        try {
            $blog = Blog::with(['producto', 'detalle', 'video', 'imagenes'])->findOrFail($id);

            // Validar producto si se está actualizando
            if (isset($data['producto_id'])) {
                $this->validateProductExists($request, $data['producto_id']);
            }

            // Procesar imagen principal si se envió una nueva
            if ($this->hasValidUploadedFile($data, 'imagen_principal')) {
                $data['imagen_principal'] = $this->updateMainImage($blog, $data['imagen_principal']);
            }

            // Actualizar blog principal
            $this->updateBlog($blog, $data);

            // Actualizar relaciones
            $this->updateBlogRelations($blog, $data);

            // Eliminar imágenes marcadas
            $this->deleteMarkedImages($blog, $data);

            // Procesar imágenes adicionales
            $this->processAdditionalImages($blog, $data);

            DB::commit();

            return $this->successResponseWithRelations($blog, 'Blog actualizado con éxito.', HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleError($e, 'Error al actualizar el blog', $id);
        }
    }

    private function validateProductExists($request, $productId): void
    {
        $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
        ]);
    }
    private function hasValidUploadedFile(array $data, string $key): bool
    {
        return !empty($data[$key]) && $data[$key] instanceof \Illuminate\Http\UploadedFile;
    }

    private function validateImage(\Illuminate\Http\UploadedFile $image, string $context = 'imagen'): void
    {
        if (!in_array($image->getMimeType(), self::VALID_MIME_TYPES)) {
            throw new \Exception(
                "El archivo de {$context} no es válido. Tipo recibido: " . $image->getMimeType()
            );
        }

        if ($image->getSize() > self::MAX_IMAGE_SIZE) {
            throw new \Exception(
                "La {$context} excede el tamaño máximo permitido de 10MB."
            );
        }
    }


    /**
     * Procesar imagen principal para creación
     */
    private function processMainImage(\Illuminate\Http\UploadedFile $image): string
    {
        $this->validateImage($image, 'imagen principal');

        try {
            $uploadedUrl = $this->storageService->uploadImage($image);

            if (!$uploadedUrl) {
                throw new \Exception("No se pudo guardar la imagen principal.");
            }

            \Log::info('Imagen principal guardada exitosamente', ['url' => $uploadedUrl]);

            return $uploadedUrl;
        } catch (\Exception $e) {
            \Log::error('Error al guardar imagen principal', ['error' => $e->getMessage()]);
            throw new \Exception("Error al guardar la imagen: " . $e->getMessage());
        }
    }

    /**
     * Actualizar imagen principal
     */
    private function updateMainImage(Blog $blog, \Illuminate\Http\UploadedFile $image): string
    {
        $this->validateImage($image, 'imagen principal');

        try {
            // Eliminar imagen anterior si existe
            if ($blog->imagen_principal && $this->isLocalStorageImage($blog->imagen_principal)) {
                $this->storageService->deleteImage($blog->imagen_principal);
                \Log::info('Imagen principal anterior eliminada', ['url' => $blog->imagen_principal]);
            }

            $uploadedUrl = $this->storageService->uploadImage($image);

            if (!$uploadedUrl) {
                throw new \Exception("No se pudo guardar la nueva imagen principal.");
            }

            \Log::info('Nueva imagen principal guardada exitosamente', ['url' => $uploadedUrl]);

            return $uploadedUrl;
        } catch (\Exception $e) {
            \Log::error('Error al guardar nueva imagen principal', ['error' => $e->getMessage()]);
            throw new \Exception("Error al guardar la imagen: " . $e->getMessage());
        }
    }

    private function createBlog(array $data): Blog
    {
        $excludedFields = [
            'imagenes',
            'video',
            'detalle',
            'titulo_blog',
            'subtitulo_beneficio',
            'url_video',
            'titulo_video'
        ];

        $blogData = array_diff_key($data, array_flip($excludedFields));

        return Blog::create($blogData);
    }

    private function updateBlog(Blog $blog, array $data): void
    {
        $excludedFields = [
            'imagenes',
            'titulo_blog',
            'subtitulo_beneficio',
            'url_video',
            'titulo_video',
            'imagenes_a_eliminar'
        ];

        $blogUpdateData = array_diff_key($data, array_flip($excludedFields));

        \Log::info('Actualizando blog con datos:', $blogUpdateData);

        $blog->update($blogUpdateData);
    }

    private function createBlogRelations(Blog $blog, array $data): void
    {
        // Crear detalle si se proporcionaron datos
        if (!empty($data['titulo_blog']) || !empty($data['subtitulo_beneficio'])) {
            $blog->detalle()->create([
                'titulo_blog' => $data['titulo_blog'] ?? null,
                'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? null,
            ]);
        }

        // Crear video si se proporcionaron datos
        if (!empty($data['url_video']) || !empty($data['titulo_video'])) {
            $blog->video()->create([
                'url_video' => $data['url_video'] ?? null,
                'titulo_video' => $data['titulo_video'] ?? null,
            ]);
        }

        // Procesar imágenes adicionales
        if (!empty($data['imagenes']) && is_array($data['imagenes'])) {
            $this->createAdditionalImages($blog, $data['imagenes']);
        }
    }

    private function updateBlogRelations(Blog $blog, array $data): void
    {
        // Actualizar o crear detalle
        $this->updateOrCreateDetail($blog, $data);

        // Actualizar o crear video
        $this->updateOrCreateVideo($blog, $data);
    }

    private function updateOrCreateDetail(Blog $blog, array $data): void
    {
        if (!isset($data['titulo_blog']) && !isset($data['subtitulo_beneficio'])) {
            return;
        }

        $detailData = [
            'titulo_blog' => $data['titulo_blog'] ?? ($blog->detalle->titulo_blog ?? null),
            'subtitulo_beneficio' => $data['subtitulo_beneficio'] ?? ($blog->detalle->subtitulo_beneficio ?? null),
        ];

        if ($blog->detalle) {
            $blog->detalle->update($detailData);
        } else {
            $blog->detalle()->create($detailData);
        }
    }

    private function updateOrCreateVideo(Blog $blog, array $data): void
    {
        if (!isset($data['url_video']) && !isset($data['titulo_video'])) {
            return;
        }

        $videoData = [
            'url_video' => $data['url_video'] ?? ($blog->video->url_video ?? null),
            'titulo_video' => $data['titulo_video'] ?? ($blog->video->titulo_video ?? null),
        ];

        if ($blog->video) {
            $blog->video->update($videoData);
        } else {
            $blog->video()->create($videoData);
        }
    }

    private function createAdditionalImages(Blog $blog, array $images): void
    {
        foreach ($images as $index => $item) {
            if (!isset($item['imagen']) || !($item['imagen'] instanceof \Illuminate\Http\UploadedFile)) {
                throw new \Exception("Falta imagen válida en el índice $index.");
            }

            $this->validateImage($item['imagen'], "imagen adicional en la posición $index");

            try {
                $uploadedUrl = $this->storageService->uploadImage($item['imagen']);

                if (!$uploadedUrl) {
                    throw new \Exception("No se pudo guardar la imagen.");
                }

                $blog->imagenes()->create([
                    'url_imagen' => $uploadedUrl,
                    'parrafo_imagen' => $item['parrafo'] ?? '',
                ]);

                \Log::info('Imagen adicional guardada', [
                    'index' => $index,
                    'url' => $uploadedUrl
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Error al guardar imagen $index: " . $e->getMessage());
            }
        }
    }

    private function deleteMarkedImages(Blog $blog, array $data): void
    {
        if (empty($data['imagenes_a_eliminar']) || !is_array($data['imagenes_a_eliminar'])) {
            return;
        }

        $imagesToDelete = $blog->imagenes()
            ->whereIn('id', $data['imagenes_a_eliminar'])
            ->get();

        foreach ($imagesToDelete as $imagen) {
            if ($imagen->url_imagen && $this->isLocalStorageImage($imagen->url_imagen)) {
                $this->storageService->deleteImage($imagen->url_imagen);
                \Log::info('Imagen adicional eliminada', [
                    'id' => $imagen->id,
                    'url' => $imagen->url_imagen
                ]);
            }
            $imagen->delete();
        }
    }

    private function processAdditionalImages(Blog $blog, array $data): void
    {
        if (empty($data['imagenes']) || !is_array($data['imagenes'])) {
            return;
        }

        foreach ($data['imagenes'] as $index => $item) {
            if (isset($item['id']) && !isset($item['imagen'])) {
                // Solo actualizar párrafo de imagen existente
                $this->updateImageParagraph($blog, $item);
            } elseif (isset($item['imagen']) && $item['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                // Subir nueva imagen o reemplazar existente
                $this->processImageUpload($blog, $item, $index);
            }
        }
    }
    private function updateImageParagraph(Blog $blog, array $item): void
    {
        if (!isset($item['parrafo'])) {
            return;
        }

        $existingImage = $blog->imagenes()->find($item['id']);
        if ($existingImage) {
            $existingImage->update(['parrafo_imagen' => $item['parrafo']]);
            \Log::info('Párrafo de imagen actualizado', ['id' => $item['id']]);
        }
    }

    private function processImageUpload(Blog $blog, array $item, int $index): void
    {
        $this->validateImage($item['imagen'], "imagen adicional en la posición $index");

        try {
            $uploadedUrl = $this->storageService->uploadImage($item['imagen']);

            if (!$uploadedUrl) {
                throw new \Exception("No se pudo guardar la imagen.");
            }

            if (isset($item['id'])) {
                // Reemplazar imagen existente
                $this->replaceExistingImage($blog, $item, $uploadedUrl);
            } else {
                // Crear nueva imagen
                $blog->imagenes()->create([
                    'url_imagen' => $uploadedUrl,
                    'parrafo_imagen' => $item['parrafo'] ?? '',
                ]);

                \Log::info('Nueva imagen adicional agregada', [
                    'index' => $index,
                    'url' => $uploadedUrl
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error al guardar imagen $index: " . $e->getMessage());
        }
    }
    private function replaceExistingImage(Blog $blog, array $item, string $newUrl): void
    {
        $existingImage = $blog->imagenes()->find($item['id']);
        if (!$existingImage) {
            return;
        }

        // Eliminar imagen anterior
        if ($existingImage->url_imagen && $this->isLocalStorageImage($existingImage->url_imagen)) {
            $this->storageService->deleteImage($existingImage->url_imagen);
        }

        // Actualizar con nueva imagen
        $existingImage->update([
            'url_imagen' => $newUrl,
            'parrafo_imagen' => $item['parrafo'] ?? $existingImage->parrafo_imagen,
        ]);

        \Log::info('Imagen adicional reemplazada', [
            'id' => $item['id'],
            'nueva_url' => $newUrl
        ]);
    }

    private function isLocalStorageImage(string $url): bool
    {
        return str_contains($url, '/storage/');
    }

    private function successResponseWithRelations(Blog $blog, string $message, HttpStatusCode $statusCode)
    {
        $blog->load(['producto', 'detalle', 'video', 'imagenes']);

        return $this->apiResponse->successResponse($blog, $message, $statusCode);
    }

    private function handleError(\Exception $e, string $baseMessage, $blogId = null)
    {
        $logData = [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];

        if ($blogId) {
            $logData['blog_id'] = $blogId;
        }

        \Log::error($baseMessage . ' failed', $logData);

        return $this->apiResponse->errorResponse(
            $baseMessage . ': ' . $e->getMessage(),
            HttpStatusCode::INTERNAL_SERVER_ERROR
        );
    }
}
