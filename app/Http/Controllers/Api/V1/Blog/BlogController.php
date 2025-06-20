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
                200,
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
