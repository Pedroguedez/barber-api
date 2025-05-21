<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $userId = $this->route('id');
        return [
                'name'     => 'sometimes|required|string|max:255',
                'email'    => 'sometimes|required|email|unique:users,email,' . $userId,
                'password' => 'sometimes|nullable|string|min:6',
                'telefone' => 'nullable|string',
                'level'    => 'sometimes|required|in:Client,Barber,Admin',
                'active'   => 'sometimes|required|boolean',
        ];
    }
}
