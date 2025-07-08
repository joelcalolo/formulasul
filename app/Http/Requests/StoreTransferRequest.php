<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // Apenas usuários autenticados podem criar transfers
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'origem' => ['required', 'string', 'max:255'],
            'destino' => ['required', 'string', 'max:255'],
            'data_hora' => ['required', 'date', 'after:now'],
            'tipo' => ['required', 'in:transfer,passeio'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
            'flight_number' => ['required', 'string', 'max:20'],
            'num_pessoas' => ['required', 'integer', 'min:1', 'max:50'],
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
            'origem.required' => 'O campo origem é obrigatório.',
            'destino.required' => 'O campo destino é obrigatório.',
            'data_hora.required' => 'O campo data e hora é obrigatório.',
            'data_hora.after' => 'A data e hora devem ser futuras.',
            'tipo.in' => 'O tipo deve ser "transfer" ou "passeio".',
            'observacoes.max' => 'As observações não podem exceder 1000 caracteres.',
            'flight_number.max' => 'O número do voo não pode exceder 20 caracteres.',
            'num_pessoas.required' => 'O campo número de pessoas é obrigatório.',
            'num_pessoas.integer' => 'O campo número de pessoas deve ser um número inteiro.',
            'num_pessoas.min' => 'O número de pessoas deve ser no mínimo 1.',
            'num_pessoas.max' => 'O número de pessoas deve ser no máximo 50.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Sanitizar observações, se necessário
        if ($this->has('observacoes')) {
            $this->merge([
                'observacoes' => trim(strip_tags($this->observacoes)),
            ]);
        }
    }
}