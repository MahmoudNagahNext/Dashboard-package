<?php

namespace nextdev\nextdashboard\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use nextdev\nextdashboard\Enums\TicketPriorityEnum;
use nextdev\nextdashboard\Enums\TicketStatusEnum;

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
            'priority'      => ['nullable', new Enum(TicketPriorityEnum::class)],
            'status'        => ['nullable', new Enum(TicketStatusEnum::class)],
            "category_id"   => "nullable|integer|exists:ticket_categories,id",
            'attachments'   => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ];
    }
}
