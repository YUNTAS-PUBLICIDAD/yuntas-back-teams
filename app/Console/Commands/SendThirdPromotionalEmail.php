<?php

namespace App\Console\Commands;

use App\Jobs\SendPromotionalEmailJob;
use App\Models\EmailProducto;
use App\Models\Interesado;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendThirdPromotionalEmail extends Command
{
    protected $signature = 'app:send-third-promotional-email';

    protected $description = 'Envio de tercer email a interesado que consultó al popup.';

    public function handle()
    {
        $now = Carbon::now('America/Lima');

        $interesados = Interesado::with(['producto', 'cliente'])
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$now->subMinutes(3)->format('Y-m-d H:i')])
            ->get();

        foreach ($interesados as $interesado) {
            $view = "emails.product-info";
            $emailProducto = EmailProducto::where('producto_id', $interesado->producto->id)->first();
            $data = [
                'name' => $interesado->cliente->name,
                'producto_nombre' => $interesado->producto->nombre,
                'producto_titulo' => $emailProducto->titulo,
                'producto_descripcion' => $emailProducto->parrafo1,
                'imagen_principal' => $emailProducto->imagen_principal,
                'imagenes_secundarias' => json_decode($emailProducto->imagenes_secundarias),
            ];
            if ($view) {
                SendPromotionalEmailJob::dispatch(
                    $interesado->cliente->email,
                    $view,
                    $data
                );
                Log::info("Publicidad enviada al interesado {$interesado->cliente->name}");
            }
        }
    }
}
