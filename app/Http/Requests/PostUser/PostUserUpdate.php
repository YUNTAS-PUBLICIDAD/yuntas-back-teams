<?php

namespace App\Http\Requests\PostUser;

use Illuminate\Foundation\Http\FormRequest;

class PostUserUpdate extends FormRequest
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
            'name' => 'required|string|max:100',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('id') . '|max:100',
            'password' => 'sometimes|string|min:1',
            'celular' => 'sometimes|nullable|string|max:15',
            'fecha' => 'sometimes|nullable|date_format:Y-m-d',
        ];
    }
}
