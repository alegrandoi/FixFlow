<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
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
        $assetId = $this->route('asset');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => ['required', 'string', 'max:255', 'unique:assets,serial_number,' . $assetId],
            'purchase_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,broken,under_maintenance,retired'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'maintenance_interval_days' => ['nullable', 'integer', 'min:1'],
            'next_maintenance_date' => ['nullable', 'date'],
        ];
    }
}
