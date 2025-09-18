<?php

namespace App\Console\Commands;

use App\Jobs\SendPromotionalEmailJob;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendFirstPromotionalEmail extends Command
{
    protected $signature = 'app:send-first-promotional-email';

    protected $description = 'Envio de publicidad luego de consultar un producto por medio del popup';

    public function handle()
    {
        $now = Carbon::now('America/Lima');

        $views = [
            'paneles-led-electronicos' => 'emails.prueba.prueba3',
            'letreros-acrilicos' => 'emails.prueba.prueba3',
        ];

        $clientes = Cliente::with('producto')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$now->subMinutes(2)->format('Y-m-d H:i')])
            ->get();

        foreach ($clientes as $cliente) {
            $view = $views[$cliente->producto->link] ?? null;

            if ($view) {
                SendPromotionalEmailJob::dispatch(
                    $cliente->email,
                    $view,
                    $cliente->name
                );
                Log::info("Publicidad enviada al cliente {$cliente->name}");
            }
        }
    }
}
