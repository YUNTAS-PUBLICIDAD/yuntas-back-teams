<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class PostStoreBlog extends FormRequest
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
            'producto_id' => 'required|integer|exists:productos,id',
            'subtitulo' => 'required|string|max:255',

            'imagen_principal' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'required|image|max:2048',

            'alt_imagenes' => 'nullable|array',
            'alt_imagenes.*' => 'nullable|string|max:255',
            'text_alt_principal' => 'nullable|string|max:255',

            'parrafos' => 'nullable|array',
            'parrafos.*' => 'required|string|max:2047',

            'url_video' => ['nullable', 'url', 'max:255'],

            // Agregar validación para los campos meta y link (por ahora link)
            'link' => [
                'nullable', 
                'string', 
                'max:255', 
                'regex:/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/', 
                Rule::unique('blogs', 'link')],
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
