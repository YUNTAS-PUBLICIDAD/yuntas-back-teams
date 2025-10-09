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
use Illuminate\Support\Facades\Storage;

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
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'secciones' => 'required|array|min:1',
            'secciones.*.titulo' => 'required|string|max:255',
            'secciones.*.parrafo1' => 'required|string',
            'secciones.*.imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'secciones.*.imagenes_secundarias.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productoId = $request->producto_id;

        // Opcional: Verificar si ya existen emails para ese producto
        $existe = EmailProducto::where('producto_id', $productoId)->exists();
        if ($existe) {
            return response()->json([
                'error' => 'Ya existen emails para este producto. Use el método PUT para actualizar.'
            ], 409);
        }

        $emailProductos = [];

        try {
            foreach ($request->secciones as $index => $seccion) {
                $data = [
                    'producto_id' => $productoId,
                    'titulo' => $seccion['titulo'],
                    'parrafo1' => $seccion['parrafo1'],
                ];

                // Procesar imagen principal usando ImageService
                if ($request->hasFile("secciones.$index.imagen_principal")) {
                    $imagenPrincipal = $request->file("secciones.$index.imagen_principal");

                    if ($this->imageService->esImagenValida($imagenPrincipal)) {
                        $data['imagen_principal'] = $this->imageService->guardarImagen(
                            $imagenPrincipal,
                            'email_productos'
                        );
                    }
                }

                // Procesar imágenes secundarias usando ImageService
                $imagenesSecundarias = [];
                if ($request->hasFile("secciones.$index.imagenes_secundarias")) {
                    foreach ($request->file("secciones.$index.imagenes_secundarias") as $imagen) {
                        if ($this->imageService->esImagenValida($imagen)) {
                            $imagenesSecundarias[] = $this->imageService->guardarImagen(
                                $imagen,
                                'email_productos'
                            );
                        }
                    }
                }

                $data['imagenes_secundarias'] = json_encode($imagenesSecundarias);

                $emailProducto = EmailProducto::create($data);
                $emailProductos[] = $emailProducto;
            }

            return response()->json([
                'message' => 'Emails Producto creados exitosamente',
                'data' => $emailProductos
            ], 201);
        } catch (Exception $e) {
            // Si hay error, limpiar las imágenes creadas
            foreach ($emailProductos as $emailProducto) {
                if ($emailProducto->imagen_principal) {
                    $this->imageService->eliminarImagen($emailProducto->imagen_principal);
                }
                if ($emailProducto->imagenes_secundarias) {
                    $imagenesSecundarias = json_decode($emailProducto->imagenes_secundarias, true);
                    if (is_array($imagenesSecundarias)) {
                        $this->imageService->eliminarImagenes($imagenesSecundarias);
                    }
                }
                $emailProducto->delete();
            }

            return response()->json([
                'error' => 'Error al crear los emails: ' . $e->getMessage()
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
