<?php

namespace nextdev\nextdashboard\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @bodyParam img file optional The user image to upload. Example: avatar.jpg
 */
class DeleteAttachmentsRequest extends FormRequest
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
            'media_ids' => 'required|array|min:1',
            'media_ids.*' => 'integer|exists:media,id',
        ];
    }
}
