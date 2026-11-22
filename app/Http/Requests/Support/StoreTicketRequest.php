<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:support_ticket_categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:5',
            'priority' => 'required|in:low,medium,high,urgent',
            'customer_id' => 'nullable|exists:customers,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,txt|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a ticket category.',
            'category_id.exists' => 'The selected category is invalid.',
            'subject.required' => 'The ticket subject is required.',
            'description.required' => 'A detailed description is required.',
            'priority.required' => 'Please select a priority level.',
            'attachments.*.max' => 'Each attachment must not exceed 10 MB.',
            'attachments.*.mimes' => 'Allowed attachment types: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, JPEG, ZIP, TXT.',
        ];
    }
}

