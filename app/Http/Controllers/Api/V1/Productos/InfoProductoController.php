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
        $resultados = [
            'email' => 'No se intentó enviar.',
            'whatsapp' => 'No se intentó enviar.'
        ];

        $cliente = Cliente::updateOrCreate(
            [
              'email' => $validated['email'], 'celular' => $validated['celular']],
            ['name' => $validated['name'], 'producto_id' => $validated['producto_id']]
        );

        $interesado = Interesado::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $validated['producto_id']
        ]);

        // 3. Buscar el Producto original para obtener su nombre
        $producto = Producto::findOrFail($validated['producto_id']);

        // 4. BUSCAR SOLO LA PRIMERA SECCIÓN (primera plantilla de correo para este producto)
       try {
        $primeraSeccion = EmailProducto::where('producto_id', $producto->id)
            ->orderBy('id', 'asc')
            ->first();

        // Si no se encuentra una plantilla personalizada para ese producto, devolvemos un error.
        if ($primeraSeccion) {
        

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
            $viewName = 'emails.firtstemail.producto-generico';
            Mail::to($cliente->email)->send(new ProductInfoMail($data, $viewName));
            $resultados['email'] = 'Correo enviado correctamente ✅';

        }  else {
                // Si NO encontramos plantilla, NO detenemos el proceso. Solo lo registramos.
                $resultados['email'] = 'No se envió correo (No se encontró plantilla de email).';
                Log::warning('No se encontró plantilla de email para producto_id: ' . $producto->id);
               } 
            }catch (\Throwable $e) {
            $resultados['email'] = '❌ Error al enviar correo: ' . $e->getMessage();
            Log::error('Error enviando email de producto: ' . $e->getMessage());
        }

        // 7. Enviar el WhatsApp
       try {
            // Revisamos si el producto tiene datos de WhatsApp guardados
            if ($producto->whatsapp_caption || $producto->whatsapp_image) {
                
                $whatsappServiceUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:5111/api');
                
                $payload = [
                    'caption' => $producto->whatsapp_caption ?? '',
                    'phone'   => "+51" . $cliente->celular,
                ];

                // Solo añadimos la imagen si existe
                if ($producto->whatsapp_image) {
                    $payload['imageData'] = $this->convertImageToBase64(
                        EmailProducto::buildImageUrl($producto->whatsapp_image)
                    );
                }

                Http::post($whatsappServiceUrl . '/send-image', $payload);
                $resultados['whatsapp'] = 'Mensaje de WhatsApp enviado correctamente ✅';

            } else {
                // Si no hay plantilla de WhatsApp, solo lo registramos.
                $resultados['whatsapp'] = 'No se envió WhatsApp (No se encontró plantilla de WhatsApp).';
                Log::warning('No se encontró plantilla de WhatsApp para producto_id: ' . $producto->id);
            }
        } catch (\Throwable $e) {
            $resultados['whatsapp'] = '❌ Error al enviar WhatsApp: ' . $e->getMessage();
            Log::error('Error enviando WhatsApp de producto: ' . $e->getMessage());
        }

        // 5. Devolver respuesta final
        return response()->json([
            'message'    => 'Proceso finalizado con los siguientes resultados:',
            'resultados' => $resultados
        ], 200);
        
    }

    public function convertImageToBase64($url)
    {
        $response = Http::withoutVerifying()->get($url);

        if (!$response->successful()) {
            throw new \Exception('No se pudo descargar la imagen');
        }

        $mimeType = $response->header('Content-Type');

        $base64 = base64_encode($response->body());

        $imageData = 'data:' . $mimeType . ';base64,' . $base64;

        return $imageData;
    }
}
