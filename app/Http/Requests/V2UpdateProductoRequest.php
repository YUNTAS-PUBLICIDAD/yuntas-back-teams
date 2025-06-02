<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class V2UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto_id = $this->route('id');
        return [
            //
            'nombre' => [
                "required",
                "string",
                "max:255",
                Rule::unique('productos', 'nombre')->ignore($producto_id),
            ],
            'link' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'link')->ignore($producto_id),
            ],
            'titulo' => "required|string|max:255",
            'subtitulo' => "required|string|max:255",
            'stock' => "required|integer|max:1000|min:0",
            'precio' => "required|string|max:100000|min:0",
            'seccion' => "required|string|max:255",
            'lema' => "required|string|max:255",
            'descripcion' => "required|string|max:65535",
            'especificaciones' => "required|string|max:65535",
            'imagenes' => "required|array|min:1|max:10",
            'imagenes.*' => "file|image|max:2048",
            'textos_alt' => "required|array|min:1|max:10",
            'textos_alt.*' => "string|max:255",
            'relacionados' => "required|array",
            'relacionados.*' => "integer|exists:productos,id",
        ];
    }
}
