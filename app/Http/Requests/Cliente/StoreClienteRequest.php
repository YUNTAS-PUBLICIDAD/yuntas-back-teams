<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productoId = $this->input('producto_id');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/',
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('clientes')
                    ->where(function ($query) use ($productoId) {
                        if ($productoId) {
                            return $query->where('producto_id', $productoId);
                        } else {
                            return $query->whereNull('producto_id');
                        }
                    }),
            ],
            'celular' => [
                'required',
                'regex:/^[0-9]{9}$/',
                Rule::unique('clientes')
                    ->where(function ($query) use ($productoId) {
                        if ($productoId) {
                            return $query->where('producto_id', $productoId);
                        } else {
                            return $query->whereNull('producto_id');
                        }
                    }),
            ],
            'producto_id' => [
                'nullable',
                //'required',
                'integer',
                'exists:productos,id'
            ]
        ];
    }

    public function messages(): array
    {
        $productoId = $this->input('producto_id');
        $contexto = $productoId ? 'para este producto' : 'en el registro general';

        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no puede exceder 100 caracteres.',
            'email.unique' => $productoId
                ? 'Este correo electrónico ya está registrado para este producto.'
                : 'Este correo electrónico ya está registrado.',

            'celular.required' => 'El número de celular es obligatorio.',
            'celular.regex' => 'El celular debe contener exactamente 9 dígitos.',
            'celular.unique' => $productoId
                ? 'Este número de celular ya está registrado para este producto.'
                : 'Este número de celular ya está registrado.',

            'producto_id.integer' => 'El ID del producto debe ser un número válido.',
            'producto_id.exists' => 'El producto seleccionado no existe.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $productoId = $this->input('producto_id');
        $contexto = $productoId ? 'producto específico' : 'registro general';

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => "Error de validación en {$contexto}. Verifica los datos proporcionados.",
            'errors' => $validator->errors(),
            'context' => [
                'type' => $productoId ? 'producto_especifico' : 'general',
                'producto_id' => $productoId
            ]
        ], 422));
    }
}
