<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkingHourRequest extends FormRequest
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
            'employee_id' => 'sometimes|exists:users,id',
            'weekday' => 'sometimes|string',
            'opening_morning' => 'sometimes|date_format:H:i',
            'closing_morning' => 'sometimes|date_format:H:i|after:opening_morning',
            'late_opening' => 'sometimes|date_format:H:i',
            'late_closing' => 'sometimes|date_format:H:i|after:late_opening',
        ];
    }
}
