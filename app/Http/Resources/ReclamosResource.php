<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReclamosResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'datos' => $this->datos,
            'tipo_doc' => $this->tipo_doc,
            'numero_doc' => $this->numero_doc,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'fecha_compra' => $this->reclamos->pluck('fecha_compra'),
            'producto' => $this->reclamos->pluck('producto'),
            'detalle_reclamo' => $this->reclamos->pluck('detalle_reclamo'),
            'monto_reclamo' => $this->reclamos->pluck('monto_reclamo'),
        ];
    }
}
