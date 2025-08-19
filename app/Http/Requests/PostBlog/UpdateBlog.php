<?php

namespace App\Http\Requests\PostBlog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateBlog extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia según tu lógica de permisos
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        // Detectar si es una actualización (viene _method=PATCH o es método PATCH)
        $isUpdate = $this->isMethod('patch') || $this->isMethod('put') || $this->input('_method') === 'PATCH' || $this->input('_method') === 'PUT';


        return [
            'producto_id' => $isUpdate ? 'sometimes|integer|exists:productos,id' : 'required|integer|exists:productos,id',
            'subtitulo' => $isUpdate ? 'sometimes|string|max:255' : 'required|string|max:255',
            'imagen_principal' => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagenes.*' => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parrafos' => $isUpdate ? 'sometimes|array|min:1' : 'required|array|min:1',
            'parrafos.*' => $isUpdate ? 'sometimes|string' : 'required|string',
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
