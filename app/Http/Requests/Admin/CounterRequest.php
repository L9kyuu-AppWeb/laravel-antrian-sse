<?php

namespace App\Http\Requests\Admin;

use App\Models\Counter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CounterRequest extends FormRequest
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
                Rule::unique(Counter::class, 'name')->ignore($this->route('counter')),
            ],
            'service_id' => ['required', 'exists:services,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
