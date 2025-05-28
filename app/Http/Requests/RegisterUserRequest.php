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
            'phone' => ['required', 'string', 'max:15', 'regex:/^\(\d{2}\)\s?(?:9)?\d{4}-\d{4}$/'],
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
            'phone.regex' => 'O telefone deve estar no formato (XX) XXXX-XXXX ou (XX) 9XXXX-XXXX.',
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
        // Normalizar telefone (remover espaços extras, formatar)
        if ($this->has('phone')) {
            $phone = preg_replace('/\D/', '', $this->phone); // Apenas números
            if (strlen($phone) === 11) {
                $phone = sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 5), substr($phone, 7));
            } elseif (strlen($phone) === 10) {
                $phone = sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 4), substr($phone, 6));
            }
            $this->merge(['phone' => $phone]);
        }
    }
}