<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Payment amount is required.',
            'amount.min' => 'Payment amount must be greater than zero.',
            'payment_date.required' => 'Payment date is required.',
            'payment_method.required' => 'Payment method is required.',
        ];
    }
}

