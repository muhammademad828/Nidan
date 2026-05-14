<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\EgyptianPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string'],
        ];
    }
}
