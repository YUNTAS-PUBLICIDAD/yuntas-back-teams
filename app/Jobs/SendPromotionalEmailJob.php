<?php

namespace App\Jobs;

use App\Mail\ProductInfoMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPromotionalEmailJob implements ShouldQueue
{
    use Queueable;

    private $email_destinario;
    private $view;
    private $name;

    public function __construct($email_destinario, $view, $name)
    {
        $this->email_destinario = $email_destinario;
        $this->view = $view;
        $this->name = $name;
    }


    public function handle(): void
    {
        try {
            $mail = new ProductInfoMail(['name' => $this->name], $this->view);
            Mail::to($this->email_destinario)->send($mail);
            Log::info('Correo enviado exitosamente a' . $this->email_destinario);
        } catch (\Exception $e) {
            Log::error('Error al enviar correo promocional a ' . $this->email_destinario . ': ' . $e->getMessage());
        }
    }
}
