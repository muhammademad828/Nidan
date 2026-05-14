<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\EgyptianPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'name' => [auth()->check() ? 'nullable' : 'required', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'guest_phone' => ['nullable', 'string', 'max:20', new EgyptianPhoneRule()],
        ];
    }
}
