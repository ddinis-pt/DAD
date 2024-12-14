<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
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
            'winner_user_id' => 'nullable|integer|exists:users,id',
            'status' => 'required|string|in:PE,PL,E,I',
            'ended_at' => 'required|date',
            'total_time' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_turns_winner' => 'nullable|integer',
        ];
    }
}
