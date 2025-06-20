<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        $blogId = $this->route('id'); 
       return [
            'titulo' => 'required|string|max:120',
            'link' => 'required|string|max:120|unique:blogs,link,' . $blogId,
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'parrafo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
            'imagen_principal' => 'required|file|image',  
            'titulo_blog' => 'required|string|max:80',
            'subtitulo_beneficio' => 'required|string|max:80',
            'url_video' => 'required|string|url',
            'titulo_video' => 'required|string|max:40',
            'imagenes' => 'required|array',
            'imagenes.*.imagen' => 'required_with:imagenes|file|image',
            'imagenes.*.parrafo' => 'required_with:imagenes|string|max:65535',
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
