<?php

namespace App\Http\Controllers\Api\V1\WhatsApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    protected $serviceUrl;

    public function __construct()
    {
        $this->serviceUrl = env('WHATSAPP_SERVICE_URL', 'http://localhost:5111/api');
    }

    /*
    * Login para obtener el token del whatsapp service
    */
    public function loginWhatsApp(Request $request) {
        $response = Http::post($this->serviceUrl . '/auth/login', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return response()->json($response->json(), $response->status());
    }

    /*
    * Generar un QR, el emisor escanea el qr para que use su número
    */
    public function requestNewQr(Request $request) {
        $token = $request->bearerToken();

        $response = Http::withToken($token)->post($this->serviceUrl . '/qr-request');

        return response()->json($response->json(), $response->status());
    }

    /*
    * Envío de mensaje - con Autorización JWT
    */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone'          => 'required|string',
            'templateOption' => 'required|string',
            'psicologo'      => 'required|string',
            'fecha'          => 'required|date',
            'hora'           => 'required|string',
        ]);

        $token = $request->bearerToken();

        $response = Http::withToken($token)
            ->post($this->serviceUrl . '/send-message', [
                'phone'          => $request->phone,
                'templateOption' => $request->templateOption,
                'psicologo'      => $request->psicologo,
                'fecha'          => $request->fecha,
                'hora'           => $request->hora,
            ]);

        return response()->json($response->json(), $response->status());
    }

    /*
    * Envío de mensaje - sin Autorización JWT
    */
    public function sendMessageAccept(Request $request) {

        $response = Http::post($this->serviceUrl . '/send-message-accept', [
            'telefono' => $request->telefono,
            'comentario' => $request->comentario,
        ]);

        return response()->json($response->json(), $response->status());
    }
}
