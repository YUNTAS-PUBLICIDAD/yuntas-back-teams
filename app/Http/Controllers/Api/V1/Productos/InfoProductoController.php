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

class InfoProductoController extends Controller
{

    public function sendProductDetails(StoreClienteRequest $request)
    {
        $validated = $request->validated();

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

        // 4. BUSCAR la plantilla de correo personalizada para este producto
        $emailProducto = EmailProducto::where('producto_id', $producto->id)->first();

        // Si no se encuentra una plantilla personalizada para ese producto, devolvemos un error.
        if (!$emailProducto) {
            return response()->json(['message' => 'No hay información promocional disponible para este producto en este momento.'], 404);
        }

        // 5. Preparar los datos para el email usando la plantilla encontrada
        $data = [
            'name' => $cliente->name,
            'producto_nombre' => $producto->nombre,
            'producto_titulo' => $emailProducto->titulo,
            'producto_descripcion' => $emailProducto->parrafo1,
            'imagen_principal' => $emailProducto->imagen_principal,
            'imagenes_secundarias' => json_decode($emailProducto->imagenes_secundarias),
        ];

        // 6. Enviar el correo
        $viewName = 'emails.product-info';
        Mail::to($cliente->email)->send(new ProductInfoMail($data, $viewName));

        // 7. Enviar el WhatsApp
        $whatsappServiceUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:5111/api');

        Http::post($whatsappServiceUrl . '/send-product-info', [
            'productName' => $producto->nombre,
            'description' => $producto->descripcion,
            'phone' => "+51 " . $cliente->celular,
            'email' => $cliente->email,
            'imageData' => $this->convertirImagenABase64('https://apiyuntas.yuntaspublicidad.com'.$emailProducto->imagen_principal),
        ]);

        // 8. Devolver respuesta
        return response()->json([
            'message' => '¡Correo con la información enviado exitosamente!'
        ], 200);
    }

    public function convertirImagenABase64($url)
    {
        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception('No se pudo descargar la imagen');
        }

        $mimeType = mime_content_type($url);

        $base64 = base64_encode($response->body());

        $imageData = 'data:' . $mimeType . ';base64,' . $base64;

        return $imageData;
    }
}
