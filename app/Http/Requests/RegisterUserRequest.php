<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Qualquer um pode se registrar
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
            'phone' => ['required', 'string', 'max:25'],
            'country' => ['required', 'string', 'in:AO,PT,ZA,NA,BR,US,CA,MZ,CV,ST,GW,GQ'],
            'role' => ['sometimes', 'in:cliente,admin'],
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está registrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.regex' => 'A senha deve conter letras maiúsculas, minúsculas, números e caracteres especiais.',
            'password.confirmed' => 'A confirmação da senha não coincide.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'country.required' => 'O campo país é obrigatório.',
            'country.in' => 'País selecionado não é válido.',
            'role.in' => 'A função deve ser "cliente" ou "admin".',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Não fazer validação customizada aqui, deixar para o método rules()
    }
}