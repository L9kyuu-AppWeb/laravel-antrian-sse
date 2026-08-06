<?php

namespace App\Http\Requests\Admin;

use App\Models\DisplayMonitor;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DisplayMonitorRequest extends FormRequest
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
                Rule::unique(DisplayMonitor::class, 'name')->ignore($this->route('display_monitor')),
            ],
            'location' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'string', 'max:255'],
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['exists:services,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
