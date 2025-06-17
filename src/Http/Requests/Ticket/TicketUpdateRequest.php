<?php

namespace nextdev\nextdashboard\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam img file optional The user image to upload. Example: avatar.jpg
 */
class TicketUpdateRequest extends FormRequest
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
            "title"         => "sometimes|string|min:3|max:255",
            "description"   => "sometimes|string",
            "priority_id"   => "sometimes|integer|exists:ticket_priorities,id",
            "status_id"     => "sometimes|integer|exists:ticket_statuses,id",
            "category_id"   => "sometimes|integer|exists:ticket_categories,id",
            "assignee_id"   => "sometimes|integer|exists:admins,id",
            'attachments'   => 'sometimes|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ];
    }
}
