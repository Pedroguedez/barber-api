<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendamentoRequest extends FormRequest
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
            'cliente_id' => 'required|exists:users,id',
            'barbeiro_id' => 'required|exists:users,id',
            'data' => 'required|date',
            'horario' => 'required|date_format:H:i',
            'status' => 'nullable|in:pendente,confirmado,cancelado,concluido',
            'observacao' => 'nullable|string',
        ];
    }
}
