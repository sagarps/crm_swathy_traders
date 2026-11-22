<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_chargeable' => 'required|in:0,1',
            'quotation_description' => 'nullable|string',
            'estimated_amount' => 'nullable|numeric|min:0',
            'estimated_completion_date' => 'nullable|date',
        ];
    }
}

