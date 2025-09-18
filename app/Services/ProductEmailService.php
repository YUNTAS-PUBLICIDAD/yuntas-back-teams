<?php

namespace App\Services;

use App\Mail\ProductInfoMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class ProductEmailService
{
    protected array $views = [
        'paneles-led-electronicos' => 'emails.prueba.prueba1',
        'letreros-acrilicos'       => 'emails.prueba.prueba2',
    ];

    public function sendByProductLink(string $email, string $name, string $link): void
    {
        if (!isset($this->views[$link])) {
            throw new Exception("No hay plantilla de email para el producto {$link}");
        }

        $view = $this->views[$link];

        try {
            Mail::to($email)->send(
                new ProductInfoMail(['name' => $name], $view)
            );
            Log::info('El correo específico se ha enviado correctamente');
        } catch (Exception $e) {
            Log::error('Error al enviar correo de producto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $email,
                'link'  => $link,
            ]);
            throw $e;
        }
    }
}
