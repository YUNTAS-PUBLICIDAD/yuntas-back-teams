<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\CorreoPersonalizado;
use App\Http\Requests\EmailRequest;
use App\Mail\ProductInfoMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ProductEmailService;
use App\Services\ImageService;
use App\Models\EmailProducto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * @OA\Tag(
 *     name="Email",
 *     description="Operaciones relacionadas con el envío de correos"
 * )
 */
class EmailController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/email",
     *     summary="Enviar un correo electrónico",
     *     description="Envía un correo personalizado a una dirección especificada usando los campos validados",
     *     operationId="sendEmail",
     *     tags={"Email"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"destinatario", "asunto", "mensaje"},
     *             @OA\Property(property="destinatario", type="string", format="email", example="destinatario@correo.com", description="Correo del destinatario"),
     *             @OA\Property(property="asunto", type="string", example="Asunto de prueba", description="Asunto del correo"),
     *             @OA\Property(property="mensaje", type="string", example="Hola, este es un mensaje enviado desde Laravel", description="Contenido del mensaje")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Correo enviado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Correo enviado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "destinatario": {"El campo destinatario es obligatorio."},
     *                     "asunto": {"El campo asunto es obligatorio."},
     *                     "mensaje": {"El campo mensaje es obligatorio."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al enviar el correo",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al enviar el correo: Error de conexión SMTP")
     *         )
     *     )
     * )
     */
    public function sendEmail(EmailRequest $request)
    {
        $datosvalidados = $request->validated();

        try {
            Mail::to($datosvalidados['destinatario'])
                ->send(new CorreoPersonalizado([
                    'asunto' => $datosvalidados['asunto'],
                    'mensaje' => $datosvalidados['mensaje']
                ]));

            return response()->json(['message' => 'Correo enviado exitosamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al enviar el correo: ' . $e->getMessage()], 500);
        }
    }

    public function showByProducto($productoId)
    {
        $plantillas = EmailProducto::where('producto_id', $productoId)->get();

        if ($plantillas->isEmpty()) {
            return response()->json(['error' => 'No se encontraron plantillas para este producto'], 404);
        }

        // Transformar las plantillas al formato esperado por el frontend
        $secciones = $plantillas->map(function ($plantilla) {
            // Decodificar las imágenes secundarias si están en formato JSON
            $imagenesSecundarias = is_string($plantilla->imagenes_secundarias)
                ? json_decode($plantilla->imagenes_secundarias, true)
                : $plantilla->imagenes_secundarias;

            // Asegurar que sea un array
            $imagenesSecundarias = is_array($imagenesSecundarias) ? $imagenesSecundarias : [];

            return [
                'id' => $plantilla->id,
                'titulo' => $plantilla->titulo ?? '',
                'parrafo1' => $plantilla->parrafo1 ?? '',
                'imagen_principal_url' => $plantilla->imagen_principal
                    ? EmailProducto::buildImageUrl($plantilla->imagen_principal)
                    : null,
                'imagen_secundaria1_url' => isset($imagenesSecundarias[0])
                    ? EmailProducto::buildImageUrl($imagenesSecundarias[0])
                    : null,
                'imagen_secundaria2_url' => isset($imagenesSecundarias[1])
                    ? EmailProducto::buildImageUrl($imagenesSecundarias[1])
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'producto_id' => $productoId,
            'secciones' => $secciones
        ], 200);
    }

    //Crear plantilla email producto

    public function store(Request $request)
    {
        $log = function (string $level, string $msg, array $ctx = []) {
            Log::channel('errorlog')->{$level}($msg, $ctx);
        };

        $traceId = (string) Str::uuid();
        $t0 = microtime(true);

        $log('info', 'EmailController@store: inicio', [
            'trace_id' => $traceId,
            'producto_id' => $request->input('producto_id'),
            'secciones_cnt' => is_array($request->input('secciones')) ? count($request->input('secciones')) : null,
        ]);

        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'secciones' => 'required|array|min:1',
            'secciones.*.titulo' => 'required|string|max:255',
            'secciones.*.parrafo1' => 'required|string',
            'secciones.*.imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'secciones.*.imagenes_secundarias.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            $log('warning', 'Validación fallida', [
                'trace_id' => $traceId,
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json(['trace_id'=>$traceId,'errors'=>$validator->errors()], 422);
        }

        $productoId = $request->producto_id;
        $existe = EmailProducto::where('producto_id', $productoId)->exists();

        error_log('DBG_QUERY='.json_encode($request->query->all()));
        error_log('DBG_BODY='.json_encode($request->request->all()));

        $quiereReemplazar =
            $request->isMethod('put') ||
            $request->input('_method') === 'PUT' ||
            $request->request->has('replace') ||
            $request->query->has('replace');

        error_log('DBG_REPLACE_EVAL='.var_export($quiereReemplazar, true).' _method='.var_export($request->input('_method'), true));

        $log('info', 'Estado inicial', [
            'trace_id'        => $traceId,
            'existe'          => $existe,
            'reemplazar_eval' => $quiereReemplazar,
        ]);

        if ($existe && !$quiereReemplazar) {
            $log('notice', 'Conflicto: existen registros y no se solicitó replace', [
                'trace_id'    => $traceId,
                'producto_id' => $productoId,
            ]);

            return response()->json([
                'trace_id' => $traceId,
                'error'    => 'Ya existen emails para este producto. Se requiere reemplazo (replace=1).',
            ], 409);
        }

        $emailProductos = [];

        try {
            $resp = DB::transaction(function () use ($request, $productoId, $existe, $quiereReemplazar, &$emailProductos, $traceId, $log) {

                if ($existe && $quiereReemplazar) {
                    $existentes = EmailProducto::where('producto_id', $productoId)->get();
                    $log('info', 'Borrando existentes', ['trace_id'=>$traceId,'count'=>$existentes->count()]);

                    foreach ($existentes as $ep) {
                        if ($ep->imagen_principal) {
                            $log('debug', 'Eliminar imagen_principal', ['trace_id'=>$traceId,'path'=>$ep->imagen_principal]);
                            $this->imageService->eliminarImagen($ep->imagen_principal);
                        }
                        if ($ep->imagenes_secundarias) {
                            $imgs = json_decode($ep->imagenes_secundarias, true);
                            if (is_array($imgs) && !empty($imgs)) {
                                $log('debug', 'Eliminar imágenes secundarias', ['trace_id'=>$traceId,'count'=>count($imgs)]);
                                $this->imageService->eliminarImagenes($imgs);
                            }
                        }
                        $ep->delete();
                    }
                }

                foreach ($request->secciones as $index => $seccion) {
                    $log('info', 'Procesando sección', [
                        'trace_id'=>$traceId, 'index'=>$index,
                        'has_img_principal'=>$request->hasFile("secciones.$index.imagen_principal"),
                        'has_imgs_sec'=>$request->hasFile("secciones.$index.imagenes_secundarias"),
                    ]);

                    $data = [
                        'producto_id' => $productoId,
                        'titulo'      => $seccion['titulo'],
                        'parrafo1'    => $seccion['parrafo1'],
                    ];

                    if ($request->hasFile("secciones.$index.imagen_principal")) {
                        $img = $request->file("secciones.$index.imagen_principal");
                        $log('debug', 'Validando img principal', [
                            'trace_id'=>$traceId, 'name'=>$img->getClientOriginalName(),
                            'size'=>$img->getSize(), 'mime'=>$img->getClientMimeType(),
                        ]);
                        if ($this->imageService->esImagenValida($img)) {
                            $ruta = $this->imageService->guardarImagen($img, 'email_productos');
                            $data['imagen_principal'] = $ruta;
                            $log('info', 'Imagen principal guardada', ['trace_id'=>$traceId,'ruta'=>$ruta]);
                        } else {
                            $log('warning', 'Imagen principal no válida', ['trace_id'=>$traceId]);
                        }
                    }

                    $secundarias = [];
                    if ($request->hasFile("secciones.$index.imagenes_secundarias")) {
                        foreach ($request->file("secciones.$index.imagenes_secundarias") as $k => $img2) {
                            $log('debug', 'Validando img secundaria', [
                                'trace_id'=>$traceId, 'idx'=>$k,
                                'name'=>$img2->getClientOriginalName(),
                                'size'=>$img2->getSize(), 'mime'=>$img2->getClientMimeType(),
                            ]);
                            if ($this->imageService->esImagenValida($img2)) {
                                $secundarias[] = $this->imageService->guardarImagen($img2, 'email_productos');
                            } else {
                                $log('warning', 'Imagen secundaria no válida', ['trace_id'=>$traceId,'idx'=>$k]);
                            }
                        }
                    }
                    $data['imagenes_secundarias'] = json_encode($secundarias);

                    $created = EmailProducto::create($data);
                    $emailProductos[] = $created;

                    $log('info', 'Sección creada', ['trace_id'=>$traceId,'seccion_id'=>$created->id]);
                }

                $msg = $quiereReemplazar ? 'Reemplazo OK' : 'Creación OK';
                $log('info', 'Éxito', ['trace_id'=>$traceId,'creados'=>count($emailProductos),'msg'=>$msg]);

                return response()->json([
                    'trace_id'=>$traceId,'message'=>$msg,'data'=>$emailProductos
                ], $quiereReemplazar ? 200 : 201);
            });

            $log('debug', 'Fin OK', ['trace_id'=>$traceId,'ms'=>round((microtime(true)-$t0)*1000)]);
            return $resp;

        } catch (Throwable $e) {
            // limpieza defensiva
            foreach ($emailProductos as $ep) {
                try {
                    if ($ep->imagen_principal) $this->imageService->eliminarImagen($ep->imagen_principal);
                    if ($ep->imagenes_secundarias) {
                        $arr = json_decode($ep->imagenes_secundarias, true) ?: [];
                        $this->imageService->eliminarImagenes($arr);
                    }
                    $ep->delete();
                } catch (Throwable $e2) {
                    $log('error', 'Error limpiando', ['trace_id'=>$traceId,'cleanup_error'=>$e2->getMessage()]);
                }
            }

            $log('error', 'EXCEPCIÓN', [
                'trace_id'=>$traceId,
                'message'=>$e->getMessage(),
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
                'code'=>$e->getCode(),
            ]);

            return response()->json([
                'trace_id'=>$traceId,
                'error'=>'Error interno al guardar la plantilla.',
            ], 500);
        }
    }


    //Actualizar plantilla email producto

    public function update(Request $request, $id)
    {
        $emailProducto = EmailProducto::find($id);

        if (!$emailProducto) {
            return response()->json(['error' => 'Email Producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'producto_id' => 'sometimes|exists:productos,id',
            'titulo' => 'sometimes|string|max:255',
            'parrafo1' => 'sometimes|string',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagenes_secundarias.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->only(['producto_id', 'titulo', 'parrafo1']);

            // Actualizar imagen principal usando ImageService
            if ($request->hasFile('imagen_principal')) {
                $imagenPrincipal = $request->file('imagen_principal');

                if ($this->imageService->esImagenValida($imagenPrincipal)) {
                    $data['imagen_principal'] = $this->imageService->actualizarImagen(
                        $imagenPrincipal,
                        $emailProducto->imagen_principal,
                        'email_productos'
                    );
                }
            }

            // Actualizar imágenes secundarias usando ImageService
            if ($request->hasFile('imagenes_secundarias')) {
                // Eliminar imágenes anteriores
                if ($emailProducto->imagenes_secundarias) {
                    $imagenesAnteriores = json_decode($emailProducto->imagenes_secundarias, true);
                    if (is_array($imagenesAnteriores)) {
                        $this->imageService->eliminarImagenes($imagenesAnteriores);
                    }
                }

                // Guardar nuevas imágenes
                $imagenesSecundarias = [];
                foreach ($request->file('imagenes_secundarias') as $imagen) {
                    if ($this->imageService->esImagenValida($imagen)) {
                        $imagenesSecundarias[] = $this->imageService->guardarImagen(
                            $imagen,
                            'email_productos'
                        );
                    }
                }
                $data['imagenes_secundarias'] = json_encode($imagenesSecundarias);
            }

            $emailProducto->update($data);

            return response()->json([
                'message' => 'Email Producto actualizado exitosamente',
                'data' => $emailProducto
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el email: ' . $e->getMessage()
            ], 500);
        }
    }
}
