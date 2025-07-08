<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreRentalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'carro_principal_id' => ['required', 'exists:cars,id'],
            'carro_secundario_id' => ['nullable', 'exists:cars,id'],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after:data_inicio'],
            'local_entrega' => ['required', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validação customizada para permitir reservas a partir de hoje
            if ($this->has('data_inicio')) {
                $dataInicio = Carbon::parse($this->data_inicio)->startOfDay();
                $hoje = Carbon::today();
                
                if ($dataInicio->lt($hoje)) {
                    $validator->errors()->add('data_inicio', 'A data de início não pode ser no passado.');
                }
            }
        });
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'carro_principal_id.required' => 'O carro principal é obrigatório.',
            'carro_principal_id.exists' => 'O carro principal selecionado não existe.',
            'data_inicio.required' => 'A data de início é obrigatória.',
            'data_inicio.date' => 'A data de início deve ser uma data válida.',
            'data_fim.after' => 'A data de fim deve ser posterior à data de início.',
            'local_entrega.required' => 'O local de entrega é obrigatório.',
            'observacoes.max' => 'As observações não podem exceder 1000 caracteres.',
        ];
    }
}