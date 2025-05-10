<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string|max:65535',
            'imagen_principal' => 'sometimes|string|url',
            'estatus' => 'nullable|string|in:borrador,publicado,archivado',
            'bloques_contenido' => 'nullable|array',
            'bloques_contenido.*.id_bloque' => 'nullable|integer|exists:bloque_contenidos,id',
            'bloques_contenido.*.parrafo' => 'nullable|string|max:5000',
            'bloques_contenido.*.imagen' => 'nullable|string|url',
            'bloques_contenido.*.descripcion_imagen' => 'nullable|string|max:255',
            'bloques_contenido.*.orden' => 'nullable|integer|min:1',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'estatus.in' => 'El estatus debe ser: borrador, publicado o archivado',
            'bloques_contenido.*.id_bloque.exists' => 'El bloque de contenido especificado no existe',
        ];
    }
}
