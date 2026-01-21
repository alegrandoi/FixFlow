<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
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
            'asset_id' => ['required', 'exists:assets,id'],
            'description' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
            'performed_at' => ['required', 'date'],
            'type' => ['required', 'in:preventive,corrective'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
