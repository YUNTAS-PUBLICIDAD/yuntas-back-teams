<?php

namespace App\Console\Commands;

use App\Jobs\SendPromotionalEmailJob;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSecondPromotionalEmail extends Command
{
    protected $signature = 'app:send-second-promotional-email';

    protected $description = 'Envio de segunda publicidad luego del primer envio';

    public function handle()
    {
        $now = Carbon::now('America/Lima');

        $views = [
            'paneles-led-electronicos' => 'emails.prueba.prueba3',
            'letreros-acrilicos' => 'emails.prueba.prueba3',
        ];

        $clientes = Cliente::with('producto')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$now->subMinutes(3)->format('Y-m-d H:i')])
            ->get();

        foreach ($clientes as $cliente) {
            $view = $views[$cliente->producto->link] ?? null;

            if ($view) {
                SendPromotionalEmailJob::dispatch(
                    $cliente->email,
                    $view,
                    $cliente->name
                );
                Log::info("Segunda publicidad enviada al cliente {$cliente->name}");
            }
        }
    }
}
