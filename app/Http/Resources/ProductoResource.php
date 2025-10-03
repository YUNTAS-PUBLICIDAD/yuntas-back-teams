<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'link' => $this->link,
            'nombre' => $this->nombre,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'seccion' => $this->seccion,
            'imagen_principal' => asset($this->imagen_principal),
            'text_alt_principal' => $this->text_alt_principal,
            'especificaciones' => $this->especificaciones ?? [],
            'beneficios' => $this->beneficios ?? [],
            'imagenes' => ProductoImagenResource::collection($this->imagenes),
            'etiqueta' => $this->etiqueta ? [
                'meta_titulo' => $this->etiqueta->meta_titulo,
                'meta_descripcion' => $this->etiqueta->meta_descripcion,
            ] : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
