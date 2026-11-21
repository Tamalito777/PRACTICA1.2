<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6',
            'rol'      => 'required|in:administrador,empleado',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required'   => 'El campo es obligatorio.',
            'email.email'  => 'El correo debe tener un formato válido.',
            'email.unique' => 'Este correo ya está registrado por otro usuario.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'rol.in'       => 'El rol seleccionado no es válido.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(response()->json([
                'mensaje' => 'error de validaciones',
                'errores' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
