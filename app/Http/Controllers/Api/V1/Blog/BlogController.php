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
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])->get();

            $showBlog = $blog->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                    'subtitulo' => $blog->subtitulo,
                    'imagen_principal' => asset($blog->imagen_principal),
                    'link' => $blog->link,
                    'imagenes' => $blog->imagenes->map(function ($imagen) {
                        return [
                            'ruta_imagen' => asset($imagen->ruta_imagen),
                            'text_alt' => $imagen->text_alt,
                        ];
                    }),
                    'parrafos' => $blog->parrafos->map(function ($parrafo) {
                        return [
                            'parrafo' => $parrafo->parrafo,
                        ];
                    }),
                    'etiqueta' => $blog->etiqueta ? [
                        'meta_titulo' => $blog->etiqueta->meta_titulo,
                        'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                    ] : null,
                    'url_video' => $blog->url_video,
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

    public function show(int $id)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->findOrFail($id);

            $showBlog = [
                'id' => $blog->id,
                'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                'subtitulo' => $blog->subtitulo,
                'imagen_principal' => asset($blog->imagen_principal),
                'text_alt_principal' => $blog->text_alt_principal, // ✅ AGREGA ESTO
                'link' => $blog->link,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => asset($imagen->ruta_imagen),
                        'text_alt' => $imagen->text_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'etiqueta' => $blog->etiqueta ? [
                    'meta_titulo' => $blog->etiqueta->meta_titulo,
                    'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                ] : null,
                'url_video' => $blog->url_video,
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

    public function showByLink(string $link)
    {
        try {
            $blog = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])
                ->where('link', $link)
                ->firstOrFail();

            $showBlog = [
                'id' => $blog->id,
                'nombre_producto' => $blog->producto ? $blog->producto->nombre : null,
                'subtitulo' => $blog->subtitulo,
                'imagen_principal' => asset($blog->imagen_principal),
                'text_alt_principal' => $blog->text_alt_principal, // ✅ AGREGA ESTO
                'link' => $blog->link,
                'imagenes' => $blog->imagenes->map(function ($imagen) {
                    return [
                        'ruta_imagen' => asset($imagen->ruta_imagen),
                        'text_alt' => $imagen->text_alt,
                    ];
                }),
                'parrafos' => $blog->parrafos->map(function ($parrafo) {
                    return [
                        'parrafo' => $parrafo->parrafo,
                    ];
                }),
                'etiqueta' => $blog->etiqueta ? [
                    'meta_titulo' => $blog->etiqueta->meta_titulo,
                    'meta_descripcion' => $blog->etiqueta->meta_descripcion,
                ] : null,
                'url_video' => $blog->url_video,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at
            ];

            return $this->apiResponse->successResponse(
                $showBlog,
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
        $blog = Blog::findOrFail($id);

        try {
            $camposActualizar = [];
            foreach (["producto_id", "subtitulo", "link", "url_video"] as $campo) {
                if (array_key_exists($campo, $datosValidados)) {
                    $camposActualizar[$campo] = $datosValidados[$campo];
                }
            }

            if ($request->hasFile('imagen_principal')) {
                $nuevaRutaImagenPrincipal = $this->imageService->actualizarImagen(
                    $request->file('imagen_principal'),
                    $blog->imagen_principal
                );
                $camposActualizar['imagen_principal'] = $nuevaRutaImagenPrincipal;

                if (isset($datosValidados['text_alt_principal'])) {
                    $camposActualizar['text_alt_principal'] = $datosValidados['text_alt_principal'];
                }
            }

            $blog->update($camposActualizar);

            if ($request->hasFile('imagenes')) {
                // Borrar imágenes y eliminar archivos antiguos
                $rutasImagenesAntiguas = $blog->imagenes->pluck('ruta_imagen')->toArray();
                if (!empty($rutasImagenesAntiguas)) {
                    $this->imageService->eliminarImagenes($rutasImagenesAntiguas);
                }
                $blog->imagenes()->delete();

                $imagenes = $request->file('imagenes');
                $altImagenes = $datosValidados['alt_imagenes'] ?? [];
                $nombreProducto = $blog->producto ? $blog->producto->nombre : '';

                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->imageService->guardarImagen($imagen);
                    $alt = $altImagenes[$i] ?? 'Imagen del blog ' . $nombreProducto;
                    $blog->imagenes()->create([
                        "ruta_imagen" => $ruta,
                        "text_alt" => $alt,
                    ]);
                }
            } else if (isset($datosValidados['alt_imagenes'])) {
                // Si solo llegan alt para imágenes existentes, actualizamos solo el texto alt
                $imagenes = $blog->imagenes;
                foreach ($imagenes as $index => $imagen) {
                    if (isset($datosValidados['alt_imagenes'][$index])) {
                        $imagen->update(['text_alt' => $datosValidados['alt_imagenes'][$index]]);
                    }
                }
            }

            if (isset($datosValidados['parrafos'])) {
                $blog->parrafos()->delete();
                foreach ($datosValidados["parrafos"] as $item) {
                    $blog->parrafos()->create([
                        "parrafo" => $item
                    ]);
                }
            }

            // ✅ PROCESAMIENTO CORREGIDO DE ETIQUETAS
            if (isset($datosValidados['etiqueta'])) {
                $blog->etiqueta()->delete(); // Eliminar etiquetas anteriores

                // Decodificar JSON si viene como string
                $etiquetaData = is_string($datosValidados['etiqueta'])
                    ? json_decode($datosValidados['etiqueta'], true)
                    : $datosValidados['etiqueta'];

                Log::info('Procesando etiqueta:', ['etiqueta_data' => $etiquetaData]);

                if (is_array($etiquetaData)) {
                    // Si es un array asociativo directo (como viene del frontend)
                    if (isset($etiquetaData['meta_titulo']) || isset($etiquetaData['meta_descripcion'])) {
                        $blog->etiqueta()->create([
                            'meta_titulo' => $etiquetaData['meta_titulo'] ?? null,
                            'meta_descripcion' => $etiquetaData['meta_descripcion'] ?? null,
                        ]);
                        Log::info('Etiqueta creada correctamente');
                    }
                    // Si es un array de arrays (formato anterior)
                    else {
                        foreach ($etiquetaData as $item) {
                            if (is_array($item)) {
                                $blog->etiqueta()->create([
                                    'meta_titulo' => $item['meta_titulo'] ?? null,
                                    'meta_descripcion' => $item['meta_descripcion'] ?? null,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            $blogActualizado = Blog::with(['imagenes', 'parrafos', 'producto', 'etiqueta'])->findOrFail($id);

            Log::info('Blog actualizado correctamente:', ['blog_id' => $id, 'subtitulo' => $blogActualizado->subtitulo]);

            return $this->apiResponse->successResponse(
                $blogActualizado,
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error actualizando blog:', ['error' => $e->getMessage(), 'blog_id' => $id]);

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
