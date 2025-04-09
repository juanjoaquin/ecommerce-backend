<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class RegistroRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRules::min(6)->letters()->symbols()->numbers()
            ]
        ];
    }
    public function messages()
    {
        return [
            'name' => 'El nombre debe ser obligatorio',
            'email' => 'El mail debe ser obligatorio',
            'email.email' => 'El email no es válido',
            'password' => 'Debe tener mínimo 6 carácteres, un número, letra y símbolo'
        ];
    }
}
