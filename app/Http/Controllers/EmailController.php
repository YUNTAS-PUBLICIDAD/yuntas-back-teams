<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\CorreoPersonalizado;
use App\Http\Requests\EmailRequest;
use Exception;

/**
 * @OA\Tag(
 *     name="Email",
 *     description="Operaciones relacionadas con el envío de correos"
 * )
 */
class EmailController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/send-email",
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
}
