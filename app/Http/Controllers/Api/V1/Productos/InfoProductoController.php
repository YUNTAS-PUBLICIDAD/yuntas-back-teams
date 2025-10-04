<?php

namespace App\Http\Controllers\Api\V1\Productos;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Producto;
use App\Models\Interesado;
use App\Models\Cliente; 
use App\Mail\ProductInfoMail;

class InfoProductoController extends Controller
{
    
    public function enviarInformacion(Request $request)
    {
        // 1. Validar los datos del popup
        $datosValidados = $request->validate([
            'email' => 'required|email',
            'nombre' => 'required|string|max:255',
            'producto_id' => 'required|integer|exists:productos,id'
        ]);

        // 2. Buscar o crear el Cliente
        $cliente = Cliente::updateOrCreate(
            ['email' => $datosValidados['email']],
            ['name' => $datosValidados['nombre']]
        );

        // 3. Buscar el Producto original para obtener su nombre
        $producto = Producto::findOrFail($datosValidados['producto_id']);

        //  4. BUSCAR la plantilla de correo personalizada para este producto
        $plantillaCorreo = Interesado::where('producto_id', $producto->id)->first();

        // Si no se encuentra una plantilla personalizada para ese producto, devolvemos un error.
        if (!$plantillaCorreo) {
            return response()->json(['message' => 'No hay información promocional disponible para este producto en este momento.'], 404);
        }

        // 5. Preparar los datos para el email usando la plantilla encontrada
        $data = [
            'name' => $cliente->name,
            'producto_nombre' => $producto->nombre, 
            'producto_titulo' => $plantillaCorreo->titulo, 
            'producto_descripcion' => $plantillaCorreo->parrafo1, 
            'imagen_principal' => $plantillaCorreo->imagen_prin, 
            'imagenes_secundarias' => json_decode($plantillaCorreo->imagenes_sec),
        ];

        // 6. Enviar el correo
        $viewName = 'emails.product-info';
        Mail::to($cliente->email)->send(new ProductInfoMail($data, $viewName));

        // 7. Devolver respuesta
        return response()->json([
            'message' => '¡Correo con la información enviado exitosamente!'
        ], 200);
    }
}