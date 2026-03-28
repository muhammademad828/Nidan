<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EgyptianPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cleaned = preg_replace('/[\s\-\(\)]+/', '', (string) $value);

        if (str_starts_with($cleaned, '+2')) {
            $cleaned = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '002')) {
            $cleaned = substr($cleaned, 3);
        }

        // Must be 11 digits starting with 010, 011, 012, or 015
        if (! preg_match('/^01[0125][0-9]{8}$/', $cleaned)) {
            $msg = app()->getLocale() === 'ar'
                ? 'رقم الهاتف يجب أن يكون رقماً مصرياً صالحاً مكوناً من 11 رقماً (يبدأ بـ 010 أو 011 أو 012 أو 015)'
                : 'Phone must be a valid Egyptian number (11 digits starting with 010, 011, 012, or 015).';
            $fail($msg);
        }
    }
}
