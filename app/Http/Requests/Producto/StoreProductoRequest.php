<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        // Log para debug
        Log::info('=== VALIDANDO REQUEST DE PRODUCTO ===');
        Log::info('Request data:', $this->all());
        Log::info('Request files:', $this->allFiles());
        
        return [
            'nombre' => 'required|string|max:255',
            'link' => 'required|string|unique:productos,link|max:255',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'lema' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'required|image',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0|max:99999999.99',
            'seccion' => 'nullable|string|max:100',
            'especificaciones' => 'sometimes|array',
            // Validación más flexible para el array de imágenes adicionales
            'imagenes' => 'sometimes|array|max:10',
            'imagenes.*' => 'sometimes|nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            // Array para identificar tipos de imagen
            'imagen_tipos' => 'sometimes|array',
            'imagen_tipos.*' => 'sometimes|string|in:imagen_hero,imagen_especificaciones,imagen_beneficios',
            // Validación para productos relacionados
            'relacionados' => 'sometimes|array',
            'relacionados.*' => 'integer|exists:productos,id'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        Log::error('=== VALIDACIÓN FALLÓ ===');
        Log::error('Errores de validación:', $validator->errors()->toArray());
        Log::error('Request data durante fallo:', $this->all());
        Log::error('Request files durante fallo:', $this->allFiles());
        
        parent::failedValidation($validator);
    }
}
