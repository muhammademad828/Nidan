<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\EgyptianPhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
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
            'guest_email' => ['nullable', 'email'],
            'guest_gender' => ['nullable', 'in:male,female,other'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer', 'exists:add_ons,id'],
            'addon_messages' => ['nullable', 'array'],
            'addon_messages.*' => ['nullable', 'string', 'max:500'],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $addonIds = $this->input('addons', []);
            if (empty($addonIds)) return;

            $addonsRequiringMessage = \App\Models\AddOn::whereIn('id', $addonIds)
                ->where('has_message', true)
                ->get();

            foreach ($addonsRequiringMessage as $addon) {
                $message = $this->input("addon_messages.{$addon->id}");
                if (empty(trim($message))) {
                    $validator->errors()->add(
                        "addon_messages.{$addon->id}", 
                        app()->getLocale() === 'ar' 
                            ? "الرسالة مطلوبة لـ {$addon->name_ar}" 
                            : "Message is required for {$addon->name_en}"
                    );
                }
            }
        });
    }
}
