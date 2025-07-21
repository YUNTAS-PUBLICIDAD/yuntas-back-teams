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

        $productId = $this->route('producto') ?? $this->route('id'); // Ajusta según tu ruta

        Log::info('=== VALIDANDO REQUEST DE ACTUALIZACIÓN DE PRODUCTO ===');
        Log::info('Request data:', $this->all());
        Log::info('Request files:', $this->allFiles());

        return [

            'link' => 'sometimes|required|string|unique:productos,link,' . $productId . '|max:255',

            'nombre' => 'sometimes|required|string|max:255',
            'titulo' => 'sometimes|required|string|max:255',
            'subtitulo' => 'sometimes|nullable|string|max:255',
            'lema' => 'sometimes|nullable|string|max:255',
            'descripcion' => 'sometimes|nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
            'precio' => 'sometimes|required|numeric|min:0|max:99999999.99',
            'seccion' => 'sometimes|nullable|string|max:100',

            'especificaciones' => 'sometimes|required|array|min:1|max:20',
            'especificaciones.*' => 'required|string|max:500|min:1',

            'imagen_principal' => 'sometimes|nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',

            // Imágenes adicionales
            'imagenes' => 'sometimes|nullable|array|max:10',
            'imagenes.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',

            // Array para tipos de imagen
            'imagen_tipos' => 'sometimes|nullable|array',
            'imagen_tipos.*' => 'string|in:imagen_hero,imagen_especificaciones,imagen_beneficios',

            // Productos relacionados 
            'productos_relacionados' => 'sometimes|nullable|array|max:10',
            'productos_relacionados.*' => 'integer|exists:productos,id|different:' . $productId,
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
