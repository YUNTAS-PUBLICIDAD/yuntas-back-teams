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

            if ($request->hasFile('imagenes')) {
                $imagenes = $request->file('imagenes');
                $altImagenes = $datosValidados['alt_imagenes'] ?? [];

                foreach ($imagenes as $i => $imagen) {
                    $ruta = $this->imageService->guardarImagen($imagen);
                    $alt = $altImagenes[$i] ?? 'Imagen del blog ' . ($blog->producto ? $blog->producto->nombre : '');
                    $blog->imagenes()->create([
                        "ruta_imagen" => $ruta,
                        "title" => "title en blogs",
                        "text_alt" => $alt,
                    ]);
                }
            }

            // Guardar cada párrafo como registro individual
            if (isset($datosValidados['parrafos']) && is_array($datosValidados['parrafos'])) {
                foreach ($datosValidados['parrafos'] as $parrafo) {
                    $blog->parrafos()->create([
                        'parrafo' => $parrafo
                    ]);
                }
            }

            // Guardar cada beneficio como registro individual
            if (isset($datosValidados['beneficios']) && is_array($datosValidados['beneficios'])) {
                foreach ($datosValidados['beneficios'] as $beneficio) {
                    $blog->beneficios()->create([
                        'beneficio' => $beneficio
                    ]);
                }
            }

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

            return $this->apiResponse->successResponse(
                $blog->fresh(),
                'Blog creado con éxito.',
                HttpStatusCode::CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->errorResponse(
                'Error al crear el blog: ' . $e->getMessage(),
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }
    }

    public function update(UpdateBlog $request, $id)
    {
        $datosValidados = $request->validated();

        DB::beginTransaction();
        $blog = Blog::with('imagenes')->findOrFail($id);

        try {
            $camposActualizar = collect(["producto_id", "subtitulo", "link", "url_video"])
                ->filter(fn($campo) => array_key_exists($campo, $datosValidados))
                ->mapWithKeys(fn($campo) => [$campo => $datosValidados[$campo]])
                ->toArray();

            if ($request->hasFile('imagen_principal')) {
                $camposActualizar['imagen_principal'] = $this->imageService->actualizarImagen(
                    $request->file('imagen_principal'),
                    $blog->imagen_principal
                );
            }

            if (isset($datosValidados['text_alt_principal'])) {
                $camposActualizar['text_alt_principal'] = $datosValidados['text_alt_principal'];
            }

            $blog->update($camposActualizar);

            $imagenesNuevas = $request->file('imagenes') ?? [];
            $altImagenes = $datosValidados['alt_imagenes'] ?? [];
            $imagenesExistentes = $blog->imagenes->sortBy('id')->values();

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
                } elseif ($nuevoAlt !== null && $imagenExistente) {
                    $imagenExistente->update(['text_alt' => $nuevoAlt]);
                }
            }

            if ($imagenesExistentes->count() > 3) {
                $excedentes = $imagenesExistentes->slice(3);
                foreach ($excedentes as $imagen) {
                    $this->imageService->eliminarImagenes([$imagen->ruta_imagen]);
                    $imagen->delete();
                }
            }

            // Actualizar párrafos: eliminar los existentes y crear los nuevos
            if (isset($datosValidados['parrafos']) && is_array($datosValidados['parrafos'])) {
                $blog->parrafos()->delete();
                foreach ($datosValidados['parrafos'] as $parrafo) {
                    $blog->parrafos()->create([
                        'parrafo' => $parrafo
                    ]);
                }
            }

            // Actualizar beneficios: eliminar los existentes y crear los nuevos
            if (isset($datosValidados['beneficios']) && is_array($datosValidados['beneficios'])) {
                $blog->beneficios()->delete();
                foreach ($datosValidados['beneficios'] as $beneficio) {
                    $blog->beneficios()->create([
                        'beneficio' => $beneficio
                    ]);
                }
            }

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

            return $this->apiResponse->successResponse(
                new BlogResource($blogActualizado),
                'Blog actualizado exitosamente',
                HttpStatusCode::OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
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
            $blog->etiqueta()->delete();
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
