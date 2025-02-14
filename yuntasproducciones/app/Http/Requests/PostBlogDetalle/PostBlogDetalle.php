<?php

namespace App\Http\Requests\PostBlogDetalle;

use Illuminate\Foundation\Http\FormRequest;

class PostBlogDetalle extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_blog' => 'exist:blog,id_blog',
            'descripcion' => 'required|string|max:40',
            'parrafo_01' => 'required|string|max:340',
            'parrafo_02' => 'required|string|max:500',
            'parrafo_03' => 'required|string|max:275',
            'img_01' => 'required|string|max:100',
            'img_02' => 'required|string|max:100',
            'img_03' => 'required|string|max:100',
        ];
    }
}
