<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnviarPrimeraPublicidad extends Command
{
    protected $signature = 'app:enviar-primera-publicidad';

    protected $description = 'Envio de publicidad luego de consultar un producto por medio del popup';

    public function handle()
    {
        //Obtener el timestamp actual
        $now = Carbon::now('America/Lima');
        
        $clientes = Cliente::all();

        foreach ($clientes as $cliente) {
            $created_at = Carbon::parse($cliente->created_at);
            //$created_at = "2025-09-16 12:25:00";

            $targetTime = $created_at->addMinutes(2);

            $producto = Product::findOrFail($cliente->producto_id);
            $link = $producto->link;

            if ($now->diffInMinutes($targetTime) == 0) {
                EnviarPublicidad::dispatch($cliente->email, $link, $cliente->name);
            }
        }
        $this->info('Publicidad enviada correctamente.');
        
    }
}
