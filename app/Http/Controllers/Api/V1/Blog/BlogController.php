<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Requests\PostBlog\PostStoreBlog;
use App\Http\Requests\PostBlog\UpdateBlog;
use App\Services\ApiResponseService;
use App\Services\ImageService;
use App\Models\Blog;
use App\Http\Contains\HttpStatusCode;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends BasicController
{

    protected ApiResponseService $apiResponse;
    protected ImageService $imageService;

    public function __construct(ApiResponseService $apiResponse, ImageService $imageService)
    {
        $this->apiResponse = $apiResponse;
        $this->imageService = $imageService;
    }
    public function index()
    {
        try {
            $perPage = request('perPage', 5);
            $page = request('page', 1);
            $blogs = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])->paginate($perPage, ['*'], 'page', $page);
            return $this->apiResponse->successResponse(
                [
                    'data' => BlogResource::collection($blogs->items()),
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                ],
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

    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->findOrFail($id);

            return $this->apiResponse->successResponse(
                new BlogResource($blog),
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

    public function showByLink(string $link)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->where('link', $link)
                ->firstOrFail();

            return $this->apiResponse->successResponse(
                new BlogResource($blog),
                'Blog obtenido exitosamente por link',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            return $this->apiResponse->errorResponse(
                'Error al obtener el blog por link: ' . $e->getMessage(),
                HttpStatusCode::NOT_FOUND
            );
        }
    }

    public function store(PostStoreBlog $request)
    {
        $datosValidados = $request->validated();

        // Log de datos validados
        Log::info('📥 Datos validados recibidos en el store:', $datosValidados);
        // Log específico para text_alt_principal
        Log::info('🖼️ ALT de imagen principal recibido:', ['text_alt_principal' => $datosValidados['text_alt_principal'] ?? 'NO RECIBIDO']);
        DB::beginTransaction();

        try {
            if (!$request->hasFile('imagen_principal')) {
                throw new \Exception('No se recibió imagen_principal como archivo');
            }

            $imagenPrincipal = $request->file("imagen_principal");
            $rutaImagenPrincipal = $this->imageService->guardarImagen($imagenPrincipal);

            $blog = Blog::create([
                "producto_id" => $datosValidados["producto_id"],
                "subtitulo" => $datosValidados["subtitulo"],
                "imagen_principal" => $rutaImagenPrincipal,
                "text_alt_principal" => $datosValidados["text_alt_principal"],
                "link" => $datosValidados["link"] ?? null,
                "url_video" => $datosValidados["url_video"] ?? null,
            ]);

            Log::info("📝 Blog creado con ID: {$blog->id}");

            // Guardar imágenes secundarias si se envían
            if ($request->hasFile('imagenes')) {
                $imagenes = $request->file('imagenes');
                $altImagenes = $datosValidados['alt_imagenes'] ?? [];

                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->imageService->guardarImagen($imagen);
                    $alt = $altImagenes[$i] ?? 'Imagen del blog ' . ($blog->producto ? $blog->producto->nombre : '');
                    $blog->imagenes()->create([
                        "ruta_imagen" => $ruta,
                        "text_alt" => $alt,
                    ]);
                    Log::info("✅ Imagen secundaria guardada: {$ruta} (ALT: {$alt})");
                }
            }

            // Guardar párrafos
            foreach ($datosValidados["parrafos"] as $item) {
                $blog->parrafos()->create([
                    "parrafo" => $item
                ]);
                Log::info("✅ Párrafo guardado: {$item}");
            }

            // Guardar etiquetas
            if ($request->has('etiqueta')) {
                $etiqueta = json_decode($request->get('etiqueta'), true);

                if (is_array($etiqueta)) {
                    $blog->etiqueta()->create([
                        'meta_titulo' => $etiqueta['meta_titulo'] ?? null,
                        'meta_descripcion' => $etiqueta['meta_descripcion'] ?? null,
                    ]);
                }
            }

            DB::commit();

            Log::info('🎉 Blog creado exitosamente');
            return $this->apiResponse->successResponse(
                $blog->fresh(),
                'Blog creado con éxito.',
                HttpStatusCode::CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error al crear blog: ' . $e->getMessage());
            return $this->apiResponse->errorResponse(
                'Error al crear el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(UpdateBlog $request, $id)
    {
        Log::info('PATCH Blog Request received:', ['request_all' => $request->all(), 'id' => $id]);
        $datosValidados = $request->validated();
        Log::info('Validated data:', ['datos_validados' => $datosValidados]);

        DB::beginTransaction();
        $blog = Blog::with('imagenes')->findOrFail($id);

        try {
            // Campos principales
            $camposActualizar = collect(["producto_id", "subtitulo", "link", "url_video"])
                ->filter(fn($campo) => array_key_exists($campo, $datosValidados))
                ->mapWithKeys(fn($campo) => [$campo => $datosValidados[$campo]])
                ->toArray();

            // Imagen principal
            if ($request->hasFile('imagen_principal')) {
                $camposActualizar['imagen_principal'] = $this->imageService->actualizarImagen(
                    $request->file('imagen_principal'),
                    $blog->imagen_principal
                );

                if (isset($datosValidados['text_alt_principal'])) {
                    $camposActualizar['text_alt_principal'] = $datosValidados['text_alt_principal'];
                }
            }

            $blog->update($camposActualizar);

            // ✅ IMÁGENES SECUNDARIAS (0-2)
            $imagenesNuevas = $request->file('imagenes') ?? [];
            $altImagenes = $datosValidados['alt_imagenes'] ?? [];
            $imagenesExistentes = $blog->imagenes->sortBy('id')->values(); // ordenadas por id

            for ($i = 0; $i < 3; $i++) {
                $nuevaImagen = $imagenesNuevas[$i] ?? null;
                $nuevoAlt = $altImagenes[$i] ?? null;
                $imagenExistente = $imagenesExistentes->get($i);

                if ($nuevaImagen) {
                    $nuevaRuta = $imagenExistente
                        ? $this->imageService->actualizarImagen($nuevaImagen, $imagenExistente->ruta_imagen)
                        : $this->imageService->guardarImagen($nuevaImagen);

                    $data = [
                        'ruta_imagen' => $nuevaRuta,
                        'text_alt' => $nuevoAlt ?? 'Imagen del blog ' . ($blog->producto->nombre ?? ''),
                    ];

                    if ($imagenExistente) {
                        $imagenExistente->update($data);
                    } else {
                        $blog->imagenes()->create($data);
                    }

                    Log::info("✅ Imagen secundaria procesada en posición {$i}", $data);
                } elseif ($nuevoAlt !== null && $imagenExistente) {
                    $imagenExistente->update(['text_alt' => $nuevoAlt]);
                    Log::info("✅ ALT actualizado en posición {$i}: {$nuevoAlt}");
                }
            }

            // 🧹 Eliminar imágenes sobrantes
            if ($imagenesExistentes->count() > 3) {
                $excedentes = $imagenesExistentes->slice(3);
                foreach ($excedentes as $imagen) {
                    $this->imageService->eliminarImagenes([$imagen->ruta_imagen]);
                    $imagen->delete();
                }
            }

            // ✅ PÁRRAFOS
            if (isset($datosValidados['parrafos'])) {
                $blog->parrafos()->delete();
                foreach ($datosValidados['parrafos'] as $parrafo) {
                    $blog->parrafos()->create(['parrafo' => $parrafo]);
                }
            }

            // ✅ ETIQUETAS
            if (isset($datosValidados['etiqueta'])) {
                $blog->etiqueta()->delete();

                $etiquetaData = is_string($datosValidados['etiqueta'])
                    ? json_decode($datosValidados['etiqueta'], true)
                    : $datosValidados['etiqueta'];

                if (is_array($etiquetaData)) {
                    $etiquetas = isset($etiquetaData['meta_titulo']) || isset($etiquetaData['meta_descripcion'])
                        ? [$etiquetaData]
                        : $etiquetaData;

                    foreach ($etiquetas as $etiqueta) {
                        if (is_array($etiqueta)) {
                            $blog->etiqueta()->create([
                                'meta_titulo' => $etiqueta['meta_titulo'] ?? null,
                                'meta_descripcion' => $etiqueta['meta_descripcion'] ?? null,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            $blogActualizado = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])->findOrFail($id);
            Log::info('✅ Blog actualizado correctamente', ['blog_id' => $id]);

            return $this->apiResponse->successResponse(
                new BlogResource($blogActualizado),
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error actualizando blog', ['error' => $e->getMessage(), 'blog_id' => $id]);

            return $this->apiResponse->errorResponse(
                'Error al actualizar el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $blog = Blog::findOrFail($id);
            $rutasImagenes = $blog->imagenes->pluck('ruta_imagen')->toArray();

            if ($blog->imagen_principal) {
                $rutasImagenes[] = $blog->imagen_principal;
            }

            $blog->imagenes()->delete();
            $blog->parrafos()->delete();
            if (!empty($rutasImagenes)) {
                $this->imageService->eliminarImagenes($rutasImagenes);
            }
            $blog->etiqueta()->delete(); // Eliminar etiquetas asociadas
            $blog->delete();

            DB::commit();

            return $this->apiResponse->successResponse(
                null,
                'Blog eliminado exitosamente',
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
}
