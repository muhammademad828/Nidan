<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\EgyptianPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_name' => ['required', 'string', 'max:190'],
            'phone' => ['required', 'string', 'max:20', new EgyptianPhoneRule()],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
