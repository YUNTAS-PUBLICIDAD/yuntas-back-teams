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
            'imagen_principal' => $isUpdate ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parrafos' => $isUpdate ? 'sometimes|array|min:1' : 'required|array|min:1',
            'parrafos.*' => $isUpdate ? 'sometimes|string' : 'required|string',
            'text_alt_principal' => 'nullable|string|max:255',
            'alt_imagenes' => 'nullable|array',
            'alt_imagenes.*' => 'nullable|string|max:255',
            // Validación para etiquetas como string JSON
            'etiqueta' => 'nullable|string|json',
            'link' => 'nullable|string|max:255',
            'url_video' => ['nullable', 'url', 'max:255'],

        ];
    }

    /**
     * Preparar datos para validación
     */
    protected function prepareForValidation()
    {
        // Si etiqueta viene como string JSON, validamos que sea JSON válido
        if ($this->has('etiqueta') && is_string($this->etiqueta)) {
            $etiquetaDecoded = json_decode($this->etiqueta, true);

            // Validar estructura de etiqueta decodificada
            if (json_last_error() === JSON_ERROR_NONE && is_array($etiquetaDecoded)) {
                // Opcional: Validar campos específicos
                if (isset($etiquetaDecoded['meta_titulo']) && strlen($etiquetaDecoded['meta_titulo']) > 255) {
                    $this->merge(['etiqueta_error' => 'meta_titulo demasiado largo']);
                }
                if (isset($etiquetaDecoded['meta_descripcion']) && strlen($etiquetaDecoded['meta_descripcion']) > 500) {
                    $this->merge(['etiqueta_error' => 'meta_descripcion demasiado largo']);
                }
            }
        }
    }

    /**
     * Get custom validation messages
     */
    public function messages()
    {
        return [
            'etiqueta.json' => 'El campo etiqueta debe ser un JSON válido.',
            'producto_id.required' => 'El producto es obligatorio.',
            'producto_id.exists' => 'El producto seleccionado no existe.',
            'subtitulo.required' => 'El subtítulo es obligatorio.',
            'imagen_principal.required' => 'La imagen principal es obligatoria.',
            'parrafos.required' => 'Debe incluir al menos un párrafo.',
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
