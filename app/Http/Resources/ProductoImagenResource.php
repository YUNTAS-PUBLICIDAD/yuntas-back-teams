<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoImagenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url_imagen' => asset($this->url_imagen),
            'texto_alt_SEO' => $this->texto_alt_SEO,
        ];
    }
}
