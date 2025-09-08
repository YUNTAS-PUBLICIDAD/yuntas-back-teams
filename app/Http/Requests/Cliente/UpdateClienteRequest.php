<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');
        $productoId = $this->input('producto_id');

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('clientes')
                    ->ignore($id)
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
                    ->ignore($id)
                    ->where(function ($query) use ($productoId) {
                        if ($productoId) {
                            return $query->where('producto_id', $productoId);
                        } else {
                            return $query->whereNull('producto_id');
                        }
                    }),
            ],
            'producto_id' => ['nullable', 'exists:productos,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'producto_id.exists' => 'El producto seleccionado no existe.',
            'producto_id' => 'sometimes|exists:productos,id',

        ];
    }
}
