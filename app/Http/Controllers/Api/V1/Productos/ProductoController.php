<?php

namespace App\Http\Controllers\Api\V1\Productos;

use App\Models\Producto;
use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\V1\BasicController;
use App\Http\Contains\HttpStatusCode;

/**
 * @OA\Tag(
 *     name="Productos",
 *     description="API Endpoints de productos"
 * )
 */
class ProductoController extends BasicController
{
    /**
     * Obtener listado de productos
     * 
     * @OA\Get(
     *     path="/api/v1/productos",
     *     summary="Muestra un listado de todos los productos",
     *     description="Retorna un array con todos los productos y sus relaciones",
     *     operationId="indexProductos",
     *     tags={"Productos"},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Producto Premium"),
     *                     @OA\Property(property="subtitle", type="string", example="La mejor calidad"),
     *                     @OA\Property(property="tagline", type="string", example="Innovación y calidad"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="specs", type="object"),
     *                     @OA\Property(property="relatedProducts", type="array", @OA\Items(type="integer")),
     *                     @OA\Property(property="images", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="image", type="string"),
     *                     @OA\Property(property="nombreProducto", type="string"),
     *                     @OA\Property(property="stockProducto", type="integer"),
     *                     @OA\Property(property="precioProducto", type="number", format="float"),
     *                     @OA\Property(property="seccion", type="string")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Productos obtenidos exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * ),
     * security={}
     */
    public function index()
    {
        $productos = Producto::with(['especificaciones', 'imagenes', 'productos_relacionados'])->get();

        $formattedProductos = $productos->map(function ($producto) {
            return [
                'id' => $producto->id,
                'link' => $producto->link, // <-- AGREGADO
                'nombreProducto' => $producto->nombre,
                'title' => $producto->titulo,
                'subtitle' => $producto->subtitulo,
                'tagline' => $producto->lema,
                'description' => $producto->descripcion,
                'stockProducto' => $producto->stock,
                'precioProducto' => $producto->precio,
                'seccion' => $producto->seccion,

                'specs' => $producto->especificaciones->pluck('valor', 'clave'),
                'relatedProducts' => $producto->productos_relacionados->pluck('id'),
                'images' => $producto->imagenes->pluck('url_imagen'),
                'image' => $producto->imagen_principal,
                
            ];
        });

        return $this->successResponse($formattedProductos, 'Productos obtenidos exitosamente');
    }

    /**
     * Crear un nuevo producto
     * 
     * @OA\Post(
     *     path="/api/v1/productos",
     *     summary="Crea un nuevo producto",
     *     description="Almacena un nuevo producto y retorna los datos creados",
     *     operationId="storeProducto",
     *     tags={"Productos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "titulo", "descripcion", "imagen_principal", "stock", "precio", "seccion"},
     *             @OA\Property(property="nombre", type="string", example="Producto XYZ"),
     *             @OA\Property(property="titulo", type="string", example="Producto Premium XYZ"),
     *             @OA\Property(property="subtitulo", type="string", example="La mejor calidad"),
     *             @OA\Property(property="lema", type="string", example="Innovación y calidad"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción detallada del producto"),
     *             @OA\Property(property="imagen_principal", type="string", example="https://placehold.co/100x150/blue/white?text=XYZ"),
     *             @OA\Property(property="stock", type="integer", example=100),
     *             @OA\Property(property="precio", type="number", format="float", example=199.99),
     *             @OA\Property(property="seccion", type="string", example="electrónica"),
     *             @OA\Property(property="especificaciones", type="object",
     *                 example={"color": "rojo", "material": "aluminio"}
     *             ),
     *             @OA\Property(property="imagenes", type="array", @OA\Items(type="string"), example={"https://placehold.co/100x150/blue/white?text=Product_X", "https://placehold.co/100x150/blue/white?text=Product_Y"}),
     *             @OA\Property(property="relacionados", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Producto creado exitosamente")
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
    public function store(StoreProductoRequest $request)
    {
        DB::beginTransaction();
        try {
            // DEBUG: Log completo de lo que llega en el request
            Log::info('=== INICIANDO CREACIÓN DE PRODUCTO ===');
            Log::info('Request all data:', $request->all());
            Log::info('Request files:', $request->allFiles());
            Log::info('¿Tiene imagenes en request?', ['has_imagenes' => $request->has('imagenes')]);
            
            // Excluimos las relaciones del request para crear solo el producto base
            $data = $request->except(['especificaciones', 'imagenes', 'relacionados', 'imagen_principal']);

            // Manejar imagen_principal (imagen para lista de productos/catálogo)
            if ($request->hasFile('imagen_principal')) {
                $imagenPrincipal = $request->file('imagen_principal');
                $nombreArchivo = time() . '_principal_' . $imagenPrincipal->getClientOriginalName();
                $rutaImagen = $imagenPrincipal->storeAs('productos', $nombreArchivo, 'public');
                $data['imagen_principal'] = '/storage/' . $rutaImagen;
            }

            // Creamos el producto
            $producto = Producto::create($data);

            // Guardamos especificaciones en la tabla relacionada
            if ($request->has('especificaciones')) {
                foreach ($request->especificaciones as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }

            // Guardamos imágenes EN ORDEN CORRECTO: [0]=HERO, [1]=Especificaciones, [2]=Beneficios
            if ($request->has('imagenes')) {
                Log::info('Imágenes recibidas en el request:', $request->imagenes);
                Log::info('Tipos de imagen recibidos:', $request->imagen_tipos ?? []);
                
                foreach ($request->imagenes as $index => $imagen) {
                    Log::info("Procesando imagen índice {$index}");
                    try {
                        if ($request->hasFile("imagenes.$index")) {
                            $archivo = $request->file("imagenes.$index");
                            Log::info("Archivo encontrado para índice {$index}:", [
                                'original_name' => $archivo->getClientOriginalName(),
                                'size' => $archivo->getSize(),
                                'mime_type' => $archivo->getMimeType(),
                                'is_valid' => $archivo->isValid()
                            ]);
                            
                            // Skip archivos vacíos o inválidos
                            if (!$archivo->isValid() || $archivo->getSize() == 0 || 
                                $archivo->getClientOriginalName() == '' || 
                                $archivo->getMimeType() == 'text/plain') {
                                Log::info("Saltando archivo vacío o inválido en índice {$index}");
                                continue;
                            }
                            
                            $nombreArchivo = time() . '_' . $index . '_' . $archivo->getClientOriginalName();
                            $rutaImagen = $archivo->storeAs('productos/adicionales', $nombreArchivo, 'public');
                            
                            if (!$rutaImagen) {
                                Log::error("Falló el almacenamiento del archivo en índice {$index}");
                                continue;
                            }
                            
                            // Determinar tipo de imagen basado en el índice o tipo enviado
                            $tipo_imagen = '';
                            if (isset($request->imagen_tipos[$index])) {
                                $tipo_key = $request->imagen_tipos[$index];
                                switch($tipo_key) {
                                    case 'imagen_hero':
                                        $tipo_imagen = 'Hero';
                                        break;
                                    case 'imagen_especificaciones':
                                        $tipo_imagen = 'Especificaciones';
                                        break;
                                    case 'imagen_beneficios':
                                        $tipo_imagen = 'Beneficios';
                                        break;
                                    default:
                                        $tipo_imagen = 'Adicional';
                                }
                            } else {
                                // Fallback basado en índice
                                switch($index) {
                                    case 0:
                                        $tipo_imagen = 'Hero';
                                        break;
                                    case 1:
                                        $tipo_imagen = 'Especificaciones';
                                        break;
                                    case 2:
                                        $tipo_imagen = 'Beneficios';
                                        break;
                                    default:
                                        $tipo_imagen = 'Adicional ' . ($index + 1);
                                }
                            }
                            
                            Log::info("Creando imagen {$tipo_imagen} con URL: /storage/{$rutaImagen}");
                            $producto->imagenes()->create([
                                'url_imagen' => '/storage/' . $rutaImagen,
                                'texto_alt_SEO' => 'Imagen ' . $tipo_imagen . ' del producto ' . $producto->nombre
                            ]);
                        } elseif (is_string($imagen)) {
                            // Si es una URL string
                            Log::info("Creando imagen desde URL string: {$imagen}");
                            $producto->imagenes()->create([
                                'url_imagen' => $imagen,
                                'texto_alt_SEO' => 'Imagen del producto ' . $producto->nombre
                            ]);
                        } else {
                            Log::warning("Imagen en índice {$index} no es archivo ni string válido");
                        }
                    } catch (\Exception $imageException) {
                        Log::error("Error procesando imagen índice {$index}: " . $imageException->getMessage());
                        // Continuamos con la siguiente imagen en lugar de fallar todo
                    }
                }
            } else {
                Log::info('No se recibieron imágenes en el request');
            }

            // Relacionamos productos
            if ($request->has('relacionados')) {
                foreach ($request->relacionados as $idRelacionado) {
                    $producto->productos_relacionados()->attach($idRelacionado);
                }
            }

            DB::commit();
            return $this->successResponse($producto, 'Producto creado exitosamente', HttpStatusCode::CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al crear el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mostrar un producto específico
     * 
     * @OA\Get(
     *     path="/api/v1/productos/{id}",
     *     summary="Muestra un producto específico",
     *     description="Retorna los datos de un producto según su ID",
     *     operationId="showProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Producto Premium"),
     *                 @OA\Property(property="subtitle", type="string", example="La mejor calidad"),
     *                 @OA\Property(property="tagline", type="string", example="Innovación y calidad"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="specs", type="object"),
     *                 @OA\Property(property="relatedProducts", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="images", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="image", type="string"),
     *                 @OA\Property(property="nombreProducto", type="string"),
     *                 @OA\Property(property="stockProducto", type="integer"),
     *                 @OA\Property(property="precioProducto", type="number", format="float"),
     *                 @OA\Property(property="seccion", type="string"),
     *                 @OA\Property(property="mensaje_correo", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="Producto encontrado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function show($id)
    {
        $producto = Producto::with(['especificaciones', 'imagenes', 'productos_relacionados'])->findOrFail($id);

        $formattedProducto = [
            'id' => $producto->id,
            'title' => $producto->titulo,
            'subtitle' => $producto->subtitulo,
            'tagline' => $producto->lema,
            'description' => $producto->descripcion,
            'specs' => $producto->especificaciones->pluck('valor', 'clave'),
            'relatedProducts' => $producto->productos_relacionados->pluck('id'),
            'images' => $producto->imagenes->pluck('url_imagen'),
            'image' => $producto->imagen_principal,
            'nombreProducto' => $producto->nombre,
            'stockProducto' => $producto->stock,
            'precioProducto' => $producto->precio,
            'seccion' => $producto->seccion,
        ];

        return $this->successResponse($formattedProducto, 'Producto obtenido exitosamente');
    }

    public function showByLink($link)
    {
        try {
            $producto = Producto::with(['especificaciones', 'imagenes', 'productos_relacionados'])->where('link', $link)->firstOrFail();

            $formattedProducto = [
                'id' => $producto->id,
                'title' => $producto->titulo,
                'subtitle' => $producto->subtitulo,
                'tagline' => $producto->lema,
                'description' => $producto->descripcion,
                'specs' => $producto->especificaciones->pluck('valor', 'clave'),
                'relatedProducts' => $producto->productos_relacionados->pluck('id'),
                'images' => $producto->imagenes->map(function($imagen) {
                    return [
                        'url_imagen' => $imagen->url_imagen,
                        'texto_alt_SEO' => $imagen->texto_alt_SEO ?? ''
                    ];
                }),
                'image' => $producto->imagen_principal,
                'nombreProducto' => $producto->nombre,
                'stockProducto' => $producto->stock,
                'precioProducto' => $producto->precio,
                'seccion' => $producto->seccion
            ];

            return $this->successResponse($formattedProducto, 'Producto encontrado exitosamente');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Producto no encontrado');
        }
    }


    /**
     * Actualizar un producto específico
     * 
     * @OA\Put(
     *     path="/api/v1/productos/{id}",
     *     summary="Actualiza un producto específico",
     *     description="Actualiza los datos de un producto existente según su ID",
     *     operationId="updateProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Producto XYZ Actualizado"),
     *             @OA\Property(property="titulo", type="string", example="Producto Premium XYZ V2"),
     *             @OA\Property(property="subtitulo", type="string", example="La mejor calidad actualizada"),
     *             @OA\Property(property="lema", type="string", example="Innovación y calidad mejorada"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción actualizada del producto"),
     *             @OA\Property(property="imagen_principal", type="string", example="https://ejemplo.com/imagen_nueva.jpg"),
     *             @OA\Property(property="stock", type="integer", example=150),
     *             @OA\Property(property="precio", type="number", format="float", example=249.99),
     *             @OA\Property(property="seccion", type="string", example="electrónica premium"),
     *             @OA\Property(property="especificaciones", type="object",
     *                 example={"color": "negro", "material": "titanio"}
     *             ),
     *             @OA\Property(property="imagenes", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="relacionados", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Producto actualizado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
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
    public function update(UpdateProductoRequest $request, $id)
    {
        try {
            $producto = Producto::findOrFail($id);
        } catch (\Exception $e) {
            return $this->notFoundResponse('Producto no encontrado');
        }

        DB::beginTransaction();
        try {
            $producto->update([
                'nombre' => $request->nombre,
                'link' => $request->link,
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'lema' => $request->lema,
                'descripcion' => $request->descripcion,
                'imagen_principal' => $request->imagen_principal,
                'stock' => $request->stock,
                'precio' => $request->precio,
                'seccion' => $request->seccion
            ]);

            if ($request->has('especificaciones')) {
                $producto->especificaciones()->delete();
                foreach ($request->especificaciones as $clave => $valor) {
                    $producto->especificaciones()->create([
                        'clave' => $clave,
                        'valor' => $valor
                    ]);
                }
            }

            // Actualizamos imágenes
            if ($request->has('imagenes')) {
                // Eliminamos imágenes anteriores
                $producto->imagenes()->delete();
                foreach ($request->imagenes as $url) {
                    $producto->imagenes()->create([
                        'url_imagen' => $url
                    ]);
                }
            }

            // Actualizamos productos relacionados
            if ($request->has('relacionados')) {
                // Sincronizamos relaciones (quita las que no están y agrega las nuevas)
                $producto->productos_relacionados()->sync($request->relacionados);
            }

            DB::commit();
            return $this->successResponse($producto, 'Producto actualizado exitosamente', HttpStatusCode::OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al actualizar el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Eliminar un producto específico
     * 
     * @OA\Delete(
     *     path="/api/v1/productos/{id}",
     *     summary="Elimina un producto específico",
     *     description="Elimina un producto existente según su ID",
     *     operationId="destroyProducto",
     *     tags={"Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="Producto eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Buscar el producto
            $producto = Producto::findOrFail($id);
            
            // Desvincular productos relacionados en lugar de eliminarlos
            $producto->productos_relacionados()->detach();
            
            // Eliminar las imágenes asociadas
            $producto->imagenes()->delete();
            
            // Eliminar especificaciones
            $producto->especificaciones()->delete();
            
            // Manejar los blogs asociados sin eliminarlos
            // Solo desvincular la relación estableciendo producto_id a null
            $blogs = $producto->blogs;
            foreach ($blogs as $blog) {
                // Actualizar el blog para desvincularlo del producto
                $blog->producto_id = null;
                $blog->save();
            }
            
            // Finalmente eliminar el producto
            $producto->delete();
            
            DB::commit();
            return $this->successResponse(null, 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->notFoundResponse('Producto no encontrado');
            }
            return $this->errorResponse('Error al eliminar el producto: ' . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
}
