<?php

namespace nextdev\nextdashboard\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam img file optional The user image to upload. Example: avatar.jpg
 */
class TicketStoreRequest extends FormRequest
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
            "title"         => "required|string|min:3|max:255",
            "description"   => "required|string",
            "priority_id"   => "nullable|integer|exists:ticket_priorities,id",
            "status_id"     => "nullable|integer|exists:ticket_statuses,id",
            "category_id"   => "nullable|integer|exists:ticket_categories,id",
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf,docx', 'max:5120'],
        ];
    }
}
