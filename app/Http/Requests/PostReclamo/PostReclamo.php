<?php

namespace App\Http\Requests\PostReclamo;

use Illuminate\Foundation\Http\FormRequest;

class PostReclamo extends FormRequest
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
            'datos' => 'required|string|max:255',
            'tipo_doc' => 'required|string|in:DNI,Pasaporte,Carnet de Extranjeria',
            'numero_doc' => 'required|string|max:20',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:15',

            'reclamos' => 'nullable|array',
            'reclamos.*.fecha_compra' => 'required|date',
            'reclamos.*.producto' => 'required|string|max:255',
            'reclamos.*.detalle_reclamo' => 'nullable|string|max:1000',
            'reclamos.*.monto_reclamo' => 'required|numeric|min:0'
        ];
    }
}
