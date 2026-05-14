<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpsertEventSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'media_url' => 'required_without:media_file|nullable|string',
            'media_file' => 'required_without:media_url|nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,wmv|max:20480',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
        ];
    }
}
