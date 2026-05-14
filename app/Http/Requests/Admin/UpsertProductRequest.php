<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $ignoreId = $product instanceof \App\Models\Product ? $product->id : $product;
        $requiredOrSometimes = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name_ar' => [$requiredOrSometimes, 'string', 'max:255'],
            'name_en' => [$requiredOrSometimes, 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:190', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'cost_price' => [$requiredOrSometimes, 'numeric', 'min:0'],
            'selling_price' => [$requiredOrSometimes, 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($ignoreId)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'is_flower' => ['nullable', 'boolean'],
            'is_customizable' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'images' => ['nullable', 'string'],
            'gallery_url_1' => ['nullable', 'string'],
            'gallery_url_2' => ['nullable', 'string'],
            'gallery_url_3' => ['nullable', 'string'],
            'gallery_url_4' => ['nullable', 'string'],
            'gallery_file_1' => ['nullable', 'image', 'max:5120'],
            'gallery_file_2' => ['nullable', 'image', 'max:5120'],
            'gallery_file_3' => ['nullable', 'image', 'max:5120'],
            'gallery_file_4' => ['nullable', 'image', 'max:5120'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }
}
