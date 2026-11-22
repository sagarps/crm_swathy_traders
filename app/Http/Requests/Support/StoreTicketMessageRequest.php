<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|min:2',
            'message_type' => 'nullable|in:reply,internal_note',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,zip,txt|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please enter a message or reply.',
            'attachments.*.max' => 'Each attachment must not exceed 10 MB.',
            'attachments.*.mimes' => 'Allowed attachment types: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, JPEG, ZIP, TXT.',
        ];
    }
}

