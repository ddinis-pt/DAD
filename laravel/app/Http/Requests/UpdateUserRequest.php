<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            "email" => "nullable|email|unique:users,email,{$userId}",
            "name" => "nullable|string",
            "nickname" => "nullable|string|unique:users,nickname,{$userId}",
            "password" => "nullable|string|min:3",
            "photo_filename" => "nullable|string",
        ];
    }
}
