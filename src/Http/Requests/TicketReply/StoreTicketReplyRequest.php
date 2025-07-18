<?php

namespace nextdev\nextdashboard\Http\Requests\TicketReply;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam attachments file[] optional The files to attach to the reply.
 */
class StoreTicketReplyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ticket_id' => 'required|exists:tickets,id',
            'body' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max file size
        ];
    }
}