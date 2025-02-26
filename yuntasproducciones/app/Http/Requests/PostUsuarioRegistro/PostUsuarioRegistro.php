<?php

namespace App\Http\Requests\PostUsuarioRegistro;

use Illuminate\Foundation\Http\FormRequest;

class PostUsuarioRegistro extends FormRequest
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
            'id_sec' => 'required|exists:seccion,id_sec',
            'nombre' => 'required|string|max:100',
            'correo' => 'required|string|max:100|email',
            'celular' => 'required|string|max:9',
            'fecha' => 'required|date',
        ];
    }
}
