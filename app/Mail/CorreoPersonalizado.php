<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorreoPersonalizado extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this
            ->subject($this->data['asunto'])
            ->view('emails.correo')
            ->with([
                'mensaje' => $this->data['mensaje'],
            ]);
    }
}
