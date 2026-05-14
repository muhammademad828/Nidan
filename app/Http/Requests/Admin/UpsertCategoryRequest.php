<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');
        $ignoreId = is_object($category) ? $category->id : null;
        $requiredOrSometimes = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name_ar' => [$requiredOrSometimes, 'string', 'max:150'],
            'name_en' => [$requiredOrSometimes, 'string', 'max:150'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:190', Rule::unique('categories', 'slug')->ignore($ignoreId)],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ];
    }
}
