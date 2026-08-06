<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Service::class, 'name')->ignore($this->route('service')),
            ],
            'prefix' => [
                'required',
                'string',
                'max:20',
                Rule::unique(Service::class, 'prefix')->ignore($this->route('service')),
            ],
            'digit_number' => ['required', 'integer', 'min:1', 'max:10'],
            'start_number' => ['required', 'integer', 'min:1'],
            'reset_number' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'service_category_id' => ['required', 'exists:service_categories,id'],
        ];
    }
}
