<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\StoreClienteRequest;
use App\Models\Cliente;
use App\Models\EmailProducto;
use App\Models\WhatsappGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InformationController extends Controller
{
    public function sendProductDetails(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'celular'      => 'required|string|max:20',
            'current_page' => 'nullable|string|max:100',
        ]);

        $resultados = [];

        $cliente = Cliente::updateOrCreate(
            [
                'email'   => $validated['email'],
                'celular' => $validated['celular'],
            ],
            [
                'name'        => $validated['name'],
            ]
        );

        $currentPage = $validated['current_page'] ?? 'raiz';
        $whatsapp = WhatsappGeneral::where('current_page', $currentPage)->first();

        if (!$whatsapp) {
            $resultados['whatsapp'] = "❌ No se encontró registro de WhatsApp para la página '{$currentPage}'";
        } else {
            try {
                $whatsappServiceUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:5111/api');

                Http::post($whatsappServiceUrl . '/send-image', [
                    'caption' => $whatsapp->caption,
                    'phone'   => "+51" . $cliente->celular,
                    'imageData' => $this->convertImageToBase64(
                        EmailProducto::buildImageUrl($whatsapp->image)
                    ),
                ]);

                $resultados['whatsapp'] = 'Mensaje de WhatsApp enviado correctamente ✅';
            } catch (\Throwable $e) {
                $resultados['whatsapp'] = '❌ Error al enviar WhatsApp: ' . $e->getMessage();
                Log::error('Error enviando WhatsApp de producto: ' . $e->getMessage());
            }
        }

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
