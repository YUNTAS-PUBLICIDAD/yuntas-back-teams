<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoWhatsAppRequest extends FormRequest
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
            'producto_id'      => ['required','integer','exists:productos,id'],
            'template_id'      => ['nullable','integer'],
            'nombre'           => ['nullable','string','max:100'], // ← cambiado
            'titulo'           => ['nullable','string','max:255'],
            'parrafo'          => ['nullable','string'],
            'imagen_principal' => ['nullable','image','mimes:jpg,jpeg,png,webp,gif','max:5120'],
        ];
    }
}
