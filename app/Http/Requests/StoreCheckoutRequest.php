<?php

namespace App\Http\Requests;

use App\Rules\EgyptianPhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'company_name'   => ['nullable', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_phone'  => ['required', 'string', new EgyptianPhone()],
            'region_id'      => ['required', 'exists:regions,id'],
            'city_id'        => ['nullable', 'exists:cities,id'],
            'address'        => ['required', 'string', 'max:500'],
            'delivery_slot_id' => ['nullable', 'exists:delivery_slots,id'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'is_gift'        => ['boolean'],
            'payment_method' => ['required', 'in:cod'],
            'terms'          => ['accepted'],
        ];

        $cartData = app(\App\Services\CartService::class)->getCartData();
        
        if (!empty($cartData['requires_delivery_slot'])) {
            $rules['delivery_date'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['delivery_date'] = ['nullable', 'date', 'after_or_equal:today'];
        }

        if (!empty($cartData['requires_delivery_time'])) {
            $rules['preferred_delivery_time'] = ['required', 'date_format:H:i'];
        } else {
            $rules['preferred_delivery_time'] = ['nullable', 'date_format:H:i'];
        }

        if ($this->boolean('is_gift')) {
            $rules['recipient_name']    = ['required', 'string', 'max:255'];
            $rules['recipient_phone']   = ['required', 'string', new EgyptianPhone()];
            $rules['recipient_address'] = ['required', 'string', 'max:500'];
            $rules['gift_message']      = ['nullable', 'string', 'max:500'];
            $rules['is_surprise']       = ['boolean'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'preferred_delivery_time.required' => app()->getLocale() === 'ar' 
                ? 'يرجى تحديد وقت الاستلام المفضل.' 
                : 'The preferred delivery time field is required.',
            'preferred_delivery_time.date_format' => app()->getLocale() === 'ar'
                ? 'صيغة الوقت غير صحيحة.'
                : 'The time format is invalid.',
            'delivery_date.required' => app()->getLocale() === 'ar'
                ? 'حقل تاريخ التوصيل مطلوب.'
                : 'The delivery date field is required.',
            'delivery_date.after_or_equal' => app()->getLocale() === 'ar'
                ? 'تاريخ التوصيل يجب أن يكون اليوم أو في المستقبل.'
                : 'The delivery date must be today or a future date.',
        ];
    }
}
