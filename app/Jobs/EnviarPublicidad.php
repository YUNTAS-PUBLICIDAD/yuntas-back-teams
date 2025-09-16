<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EnviarPublicidad implements ShouldQueue
{
    use Queueable;

    private $email_destinario;
    private $link;
    private $name;

    public function __construct($email_destinario, $link, $name)
    {
        $this->email_destinario = $email_destinario;
        $this->link = $link;
        $this->name = $name;
    }

    public function handle(): void
    {
        $views = [
            'paneles-led-electronicos'          => 'emails.prueba.prueba3',
            'letreros-acrilicos'                => 'emails.prueba.prueba3',
        ];

        if (!isset($views[$link])) {
            abort(404, "No hay plantilla de email para el producto {$link}");
        }

        $view = $views[$link];

        Mail::to($this->email_destinario)->send(new ProductInfoMail(['name' => $this->name], $view));
    }
}
