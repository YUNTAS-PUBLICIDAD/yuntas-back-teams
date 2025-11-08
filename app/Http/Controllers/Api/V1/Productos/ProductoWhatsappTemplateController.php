<?php

namespace App\Http\Controllers\Api\v1\Productos;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoWhatsAppRequest;
use App\Models\Producto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoWhatsappTemplateController extends Controller
{
    public function upsertBasic(StoreProductoWhatsAppRequest $request, $productoId)
    {
        try {
            $producto = \App\Models\Producto::findOrFail($productoId);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        DB::beginTransaction();
        try {
            // Texto: unifica titulo + parrafo en una sola columna (whatsapp_caption)
            $titulo  = trim((string)$request->input('titulo', ''));
            $parrafo = trim((string)$request->input('parrafo', ''));
            $caption = $titulo !== '' ? ($titulo . ($parrafo !== '' ? "\n".$parrafo : '')) : $parrafo;

            if ($caption !== '') {
                $producto->whatsapp_caption = $caption;
            }

            // Imagen
            if ($request->boolean('eliminar_imagen')) {
                $producto->whatsapp_image = null;
            }

            if ($request->hasFile('imagen_principal')) {
                $img = $request->file('imagen_principal');
                $nombreArchivo = time().'_whatsapp_'.$img->getClientOriginalName();
                $ruta = $img->storeAs('productos/whatsapp', $nombreArchivo, 'public'); // storage/app/public/...
                $producto->whatsapp_image = '/storage/'.$ruta; // asegúrate de tener el symlink: php artisan storage:link
            }

            $producto->save();
            DB::commit();

            return response()->json([
                'data' => [
                    'producto_id'        => $producto->id,
                    'whatsapp_image'     => $producto->whatsapp_image,
                    'whatsapp_image_url' => $producto->whatsapp_image ? asset($producto->whatsapp_image) : null,
                    'titulo'             => $titulo,
                    'parrafo1'           => $parrafo,
                    'whatsapp_caption'   => $producto->whatsapp_caption, // 👈 crudo
                ]
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error upsert WhatsApp basic: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Error al guardar plantilla'], 500);
        }
    }

    public function showBasic($productoId)
    {
        $producto = \App\Models\Producto::findOrFail($productoId);

        if (empty($producto->whatsapp_image) && empty($producto->whatsapp_caption)) {
            return response()->json(['message' => 'Sin plantilla'], 404);
        }

        return response()->json([
            'data' => [
                'producto_id'        => $producto->id,
                'whatsapp_image'     => $producto->whatsapp_image,
                'whatsapp_image_url' => $producto->whatsapp_image ? asset($producto->whatsapp_image) : null,
                // ⟵ Importante: manda SIEMPRE el caption completo
                'parrafo1'           => (string) $producto->whatsapp_caption,
            ],
        ], 200);
    }
}