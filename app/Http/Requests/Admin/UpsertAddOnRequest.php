<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpsertAddOnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $requiredOrSometimes = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name_ar' => [$requiredOrSometimes, 'string', 'max:150'],
            'name_en' => [$requiredOrSometimes, 'string', 'max:150'],
            'price' => [$requiredOrSometimes, 'numeric', 'min:0'],
            'cost_price' => [$requiredOrSometimes, 'numeric', 'min:0'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'has_message' => ['nullable'],
            'placeholder_ar' => ['nullable', 'string', 'max:255'],
            'placeholder_en' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
            'has_message' => $this->has('has_message'),
        ]);
    }
}
