<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameUpdateRequest extends FormRequest
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
            'status' => 'required|string|in:PE,PL,E,I',
            'winner_user_id' => 'required|integer|exists:users,id',
            'ended_at' => 'required|date',
            'total_time' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'total_turns_winner' => 'required|integer',
        ];
    }
}
