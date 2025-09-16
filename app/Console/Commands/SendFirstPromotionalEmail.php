<?php

namespace App\Console\Commands;

use App\Jobs\SendPromotionalEmailJob;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendFirstPromotionalEmail extends Command
{
    protected $signature = 'app:enviar-primera-publicidad';

    protected $description = 'Envio de publicidad luego de consultar un producto por medio del popup';

    public function handle()
    {
        $now = Carbon::now('America/Lima');

        $clientes = Cliente::all();

        $views = [
            'paneles-led-electronicos' => 'emails.prueba.prueba3',
            'letreros-acrilicos' => 'emails.prueba.prueba3',
        ];

        foreach ($clientes as $cliente) {
            $created_at = Carbon::parse($cliente->created_at);

            $targetTime = $created_at->addMinutes(2);

            $producto = Producto::findOrFail($cliente->producto_id);

            $view = $views[$producto->link] ?? null;

            if ($now->format('Y-m-d H:i') == $targetTime->format('Y-m-d H:i')) {
                SendPromotionalEmailJob::dispatch($cliente->email, $view, $cliente->name);
                Log::info('Publicidad para el cliente' . $cliente->name . ' enviada al job.');
            }
        }
    }
}
