<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre_producto' => $this->producto ? $this->producto->nombre : null,
            'subtitulo' => $this->subtitulo,
            'imagen_principal' => asset($this->imagen_principal),
            'text_alt_principal' => $this->text_alt_principal,
            'link' => $this->link,
            'imagenes' => BlogImagenResource::collection($this->imagenes),
            'parrafos' => BlogParrafoResource::collection($this->parrafos),
            'etiqueta' => $this->etiqueta ? [
                'meta_titulo' => $this->etiqueta->meta_titulo,
                'meta_descripcion' => $this->etiqueta->meta_descripcion,
            ] : null,
            'url_video' => $this->url_video,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
