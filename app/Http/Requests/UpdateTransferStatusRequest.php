<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransferStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', 'in:confirmado,rejeitado'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'confirmation_method' => ['nullable', 'in:admin_panel,email,sms,phone'],
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
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser "confirmado" ou "rejeitado".',
            'admin_notes.max' => 'As observações não podem exceder 1000 caracteres.',
            'confirmation_method.in' => 'O método de confirmação deve ser válido.',
        ];
    }
}
