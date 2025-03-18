<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
        return [
            'nombre' => 'required|string',
            'titulo' => 'required|string',
            'imagen_principal' => 'required|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'especificaciones' => 'array',
            'dimensiones' => 'array',
            'imagenes' => 'array',
            'relacionados' => 'array',
            'mensaje_correo' => 'nullable|string'
        ];
    }
}
