<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveGameRequest extends FormRequest
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
            'created_user_id' => 'required|integer|exists:users,id',
            'type' => 'required|string|in:S,M',
            'status' => 'required|string|in:PE,PL,E,I',
            'began_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'total_time' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'board_id' => 'required|integer|in:1,2,3',
            'total_turns_winner' => 'nullable|integer',
        ];
    }
}
