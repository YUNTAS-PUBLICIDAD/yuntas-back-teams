<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'imagen_principal' => 'required|string',
            'estatus' => 'nullable|string|in:borrador,publicado,archivado',
            'bloques_contenido' => 'nullable|array',
            'bloques_contenido.*.parrafo' => 'nullable|string',
            'bloques_contenido.*.imagen' => 'nullable|string',
            'bloques_contenido.*.descripcion_imagen' => 'nullable|string',
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
            'titulo.required' => 'El título es obligatorio',
            'descripcion.required' => 'La descripción es obligatoria',
            'imagen_principal.required' => 'La imagen principal es obligatoria',
            'estatus.in' => 'El estatus debe ser: borrador, publicado o archivado',
        ];
    }
}
