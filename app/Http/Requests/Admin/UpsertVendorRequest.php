<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\EgyptianPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class UpsertVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $requiredOrSometimes = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name' => [$requiredOrSometimes, 'string', 'max:150'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20', new EgyptianPhoneRule()],
            'email' => ['nullable', 'email'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
