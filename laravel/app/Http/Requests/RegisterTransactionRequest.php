<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTransactionRequest extends FormRequest
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
            'transaction_datetime' => 'required|date',
            'user_id' => 'required|integer|exists:users,id',
            'type' => 'required|string|in:B,P,I',
            'brain_coins' => 'required|integer',
            'game_id' => 'required_if:type,I|integer|exists:games,id',
            'euros' => 'required_if:type,P|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'payment_type' => 'required_if:type,P|string|in:MBWAY,IBAN,MB,PAYPAL,VISA',
            'payment_reference' => 'required_if:type,P|string',
        ];
    }
}
