<?php

namespace App\Http\Controllers\Api\V1\Productos;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Producto;
use App\Models\Interesado;
use App\Mail\ProductInfoMail;

class InfoProductoController extends Controller
{
    /**
     * Recibe la solicitud del popup, guarda al interesado y envía el correo.
     */

public function enviarInformacion(Request $request)
{
    // 1. Validar datos (igual que antes)
    $datosValidados = $request->validate([
        'email' => 'required|email',
        'nombre' => 'required|string|max:255',
        'producto_id' => 'required|integer|exists:productos,id'
    ]);

    // 2. Encontrar el producto Y CARGAR SUS IMÁGENES RELACIONADAS
    $producto = Producto::with('imagenes')->findOrFail($datosValidados['producto_id']);

    // 3. Guardar al interesado (igual que antes)
    Interesado::create([
        'email' => $datosValidados['email'],
        'nombre' => $datosValidados['nombre'],
        'producto_id' => $producto->id,
    ]);

    // 4. Preparamos los datos, AHORA INCLUYENDO LAS IMÁGENES
    $data = [
        'name' => $datosValidados['nombre'],
        'producto_nombre' => $producto->nombre,
        'producto_titulo' => $producto->titulo, // El subtítulo del diseño
        'producto_descripcion' => $producto->descripcion,
        'imagen_principal' => $producto->imagen_principal,
        // Tomamos las primeras 2 imágenes de la relación.
        // Asumo que tu modelo ProductoImagenes tiene un campo 'ruta_imagen' o similar.
        'imagenes_secundarias' => $producto->imagenes->take(2) 
    ];

    // 5. Apuntamos a nuestra nueva vista de correo
    $viewName = 'emails.product-info';

    // 6. Enviamos el correo (igual que antes)
    Mail::to($datosValidados['email'])->send(new ProductInfoMail($data, $viewName));

    // 7. Devolver respuesta (igual que antes)
    return response()->json([
        'message' => '¡Correo con la información enviado exitosamente!'
    ], 200);
}
}