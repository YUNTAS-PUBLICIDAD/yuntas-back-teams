<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        
        $productoId = $this->route('id') ?? $this->route('producto');

        Log::info('=== VALIDANDO REQUEST DE ACTUALIZACIÓN DE PRODUCTO ===');
        Log::info('Request data:', $this->all());
        Log::info('Request files:', $this->allFiles());

        return [
            'nombre' => 'sometimes|string|max:255',
            'link' => "sometimes|string|unique:productos,link,{$productoId}|max:255",
            'titulo' => 'sometimes|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'lema' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|image',
            'stock' => 'sometimes|integer|min:0',
            'precio' => 'sometimes|numeric|min:0|max:99999999.99',
            'seccion' => 'nullable|string|max:100',

            'especificaciones' => 'sometimes|array',

            'imagenes' => 'sometimes|array|max:10',
            'imagenes.*' => 'sometimes|nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',

            'imagen_tipos' => 'sometimes|array',
            'imagen_tipos.*' => 'sometimes|string|in:imagen_hero,imagen_especificaciones,imagen_beneficios',

            'relacionados' => 'sometimes|array',
            'relacionados.*' => 'integer|exists:productos,id'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('=== VALIDACIÓN DE UPDATE FALLÓ ===');
        Log::error('Errores:', $validator->errors()->toArray());
        Log::error('Request data:', $this->all());
        Log::error('Request files:', $this->allFiles());

        parent::failedValidation($validator);
    }
}
