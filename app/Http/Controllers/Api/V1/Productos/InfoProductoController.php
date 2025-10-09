<?php

namespace App\Http\Controllers\Api\V1\Productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\StoreClienteRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\Producto;
use App\Models\Interesado;
use App\Models\Cliente;
use App\Mail\ProductInfoMail;
use App\Models\EmailProducto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InfoProductoController extends Controller
{

    public function sendProductDetails(StoreClienteRequest $request)
    {
        $validated = $request->validated();
        $resultados = [];

        $cliente = Cliente::updateOrCreate(
            [
                'email'   => $validated['email'],
                'celular' => $validated['celular'],
            ],
            [
                'name'        => $validated['name'],
                'producto_id' => $validated['producto_id'],
            ]
        );

        $interesado = Interesado::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $validated['producto_id']
        ]);

        // 3. Buscar el Producto original para obtener su nombre
        $producto = Producto::findOrFail($validated['producto_id']);

        // 4. BUSCAR SOLO LA PRIMERA SECCIÓN (primera plantilla de correo para este producto)
        $primeraSeccion = EmailProducto::where('producto_id', $producto->id)
            ->orderBy('id', 'asc')
            ->first();

        // Si no se encuentra una plantilla personalizada para ese producto, devolvemos un error.
        if (!$primeraSeccion) {
            return response()->json(['message' => 'No hay información promocional disponible para este producto en este momento.'], 404);
        }

        // 5. Preparar los datos para el email usando SOLO la primera sección
        $data = [
            'name' => $cliente->name,
            'producto_nombre' => $producto->nombre,
            'producto_titulo' => $primeraSeccion->titulo,
            'producto_descripcion' => $primeraSeccion->parrafo1,
            'imagen_principal' => EmailProducto::buildImageUrl($primeraSeccion->imagen_principal),
            'imagenes_secundarias' => array_map(
                fn($img) => EmailProducto::buildImageUrl($img),
                json_decode($primeraSeccion->imagenes_secundarias, true) ?? []
            )
        ];

        // 6. Enviar el correo
        try {
            $viewName = 'emails.firtstemail.producto-generico';
            Mail::to($cliente->email)->send(new ProductInfoMail($data, $viewName));
            $resultados['email'] = 'Correo enviado correctamente ✅';
        } catch (\Throwable $e) {
            $resultados['email'] = '❌ Error al enviar correo: ' . $e->getMessage();
            Log::error('Error enviando email de producto: ' . $e->getMessage());
        }

        // 7. Enviar el WhatsApp
        try {
            $whatsappServiceUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:5111/api');

            Http::post($whatsappServiceUrl . '/send-product-info', [
                'productName' => $producto->nombre,
                'description' => $primeraSeccion->parrafo1, // Usar descripción de la primera sección
                'phone'       => "+51" . $cliente->celular,
                'email'       => $cliente->email,
                'imageData'   => $this->convertImageToBase64(
                    EmailProducto::buildImageUrl($primeraSeccion->imagen_principal)
                ),
            ]);
            $resultados['whatsapp'] = 'Mensaje de WhatsApp enviado correctamente ✅';
        } catch (\Throwable $e) {
            $resultados['whatsapp'] = '❌ Error al enviar WhatsApp: ' . $e->getMessage();
            Log::error('Error enviando WhatsApp de producto: ' . $e->getMessage());
        }

        // 8. Devolver respuesta con detalles
        return response()->json([
            'message'   => 'Proceso finalizado con los siguientes resultados:',
            'resultados' => $resultados
        ], 200);
    }

    public function convertImageToBase64($url)
    {
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception('No se pudo descargar la imagen');
        }

        $mimeType = $response->header('Content-Type');

        $base64 = base64_encode($response->body());

        $imageData = 'data:' . $mimeType . ';base64,' . $base64;

        return $imageData;
    }
}
