<?php

namespace App\Http\Requests\PostProductoDetalle;

use Illuminate\Foundation\Http\FormRequest;

class PostProductoDetalle extends FormRequest
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
            'id_produc' => 'exist:producto,id_produc',
            'especificacion' => 'required|string|max:40',
            'informacion' => 'required|string|max:255',
            'beneficios_01' => 'required|string|max:40',
            'beneficios_02' => 'required|string|max:40',
            'beneficios_03' => 'required|string|max:40',
            'beneficios_04' => 'required|string|max:40',
            'img_card' => 'required|string|max:100',
            'img_portada_01' => 'required|string|max:100',
            'img_portada_02' => 'required|string|max:100',
            'img_portada_03' => 'required|string|max:100',
            'img_esp' => 'required|string|max:100',
            'img_benef' => 'required|string|max:100',
        ];
    }
}
