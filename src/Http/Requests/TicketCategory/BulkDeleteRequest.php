<?php 
namespace nextdev\nextdashboard\Http\Requests\TicketCategory;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:ticket_categories,id',
        ];
    }
}
