<?php

namespace App\Services;

use App\Mail\ProductInfoMail;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class ProductEmailService
{
    private function getProductTexts(string $productoNombre): array
    {
        $textos = [
            'relojes-digitales' => [
                'header' => '¡SINCRONIZA TU NEGOCIO CON EL TIEMPO!',
                'tagline' => '¡Mantén a tus clientes informados con estilo y precisión!'
            ],
            'menu-board' => [
                'header' => '¡PRESENTA TU MENÚ CON IMPACTO!',
                'tagline' => '¡Captura la atención y aumenta tus ventas con diseños irresistibles!'
            ],
            'luces-led' => [
                'header' => '¡ILUMINA TU NEGOCIO CON ESTILO!',
                'tagline' => '¡Crea ambientes únicos y enamora todas las miradas!'
            ],
        ];

        return $textos[$productoNombre] ?? [
            'header' => '¡TRANSFORMA TU NEGOCIO!',
            'tagline' => '¡Destaca con nuestras soluciones profesionales!'
        ];
    }

    public function sendEmailByProductLink(string $email, string $name, string $link): void
    {
        $producto = Producto::where('link', $link)->with('imagenes')->first();

        if (!$producto) {
            throw new Exception("Producto no encontrado con el link: {$link}");
        }

        $textos = $this->getProductTexts($link);

        // Incluir 'name' en los datos
        $data = [
            'name' => $name, // ✅ Agregado de nuevo
            'header' => $textos['header'],
            'tagline' => $textos['tagline'],
            'imagen_principal' => $producto->imagen_principal,
            'imagenes_secundarias' => $producto->imagenes->take(2),
        ];

        try {
            Mail::to($email)->send(
                new ProductInfoMail($data, 'emails.firtstemail.producto-generico')
            );
            Log::info('Correo enviado correctamente', ['email' => $email]);
        } catch (Exception $e) {
            Log::error('Error al enviar correo', [
                'error' => $e->getMessage(),
                'email' => $email,
                'link'  => $link,
            ]);
            throw $e;
        }
    }
}
