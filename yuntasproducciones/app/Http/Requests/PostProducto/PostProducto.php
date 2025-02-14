<?php

namespace App\Http\Requests\PostProducto;

use Illuminate\Foundation\Http\FormRequest;

class PostProducto extends FormRequest
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
            'titulo' => 'required|string|max:50',
            'imagen' => 'required|string|max:100',
        ];
    }
}
