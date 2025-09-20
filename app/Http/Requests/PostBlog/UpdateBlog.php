<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBlog extends FormRequest
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
    public function rules()
    {
        return [
            'producto_id' => 'sometimes|integer|exists:productos,id',
            'subtitulo' => 'sometimes|string|max:255',

            'imagen_principal' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            // ✅ Validación mejorada para imágenes con índices específicos
            'imagenes' => 'nullable|array|max:3',
            'imagenes.0' => 'sometimes|image|max:2048',
            'imagenes.1' => 'sometimes|image|max:2048',
            'imagenes.2' => 'sometimes|image|max:2048',

            // ✅ Validación mejorada para alt images con índices específicos  
            'alt_imagenes' => 'nullable|array|max:3',
            'alt_imagenes.0' => 'nullable|string|max:255',
            'alt_imagenes.1' => 'nullable|string|max:255',
            'alt_imagenes.2' => 'nullable|string|max:255',

            'text_alt_principal' => 'sometimes|string|max:255',

            'parrafos' => 'nullable|array',
            'parrafos.*' => 'required|string|max:2047',

            'url_video' => ['nullable', 'url', 'max:255'],

            'link' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],

            // ✅ Validación para etiquetas (puede venir como string JSON o array)
            'etiqueta' => 'nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
