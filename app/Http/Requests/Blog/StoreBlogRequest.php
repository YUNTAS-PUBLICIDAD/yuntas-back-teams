<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'link' => 'required|string|max:255',
            'producto_id' => ['nullable', 'integer', 'exists:productos,id'],
            'parrafo' => 'required|string',
            'descripcion' => 'required|string',
            'imagen_principal' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'titulo_blog' => 'required|string',
            'subtitulo_beneficio' => 'required|string',
            'url_video' => 'required|url',
            'titulo_video' => 'required|string',
            'imagenes' => 'required|array',
            'imagenes.*.imagen' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'imagenes.*.parrafo' => 'nullable|string|max:500',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
