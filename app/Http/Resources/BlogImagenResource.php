<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogImagenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ruta_imagen' => asset($this->ruta_imagen),
            'text_alt' => $this->text_alt,
        ];
    }
}
