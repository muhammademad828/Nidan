<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class EgyptianPhoneRule implements ValidationRule
{
    protected string $pattern = '/^01[0125][0-9]{8}$/';

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match($this->pattern, $value)) {
            $fail('The :attribute must be a valid Egyptian phone number (e.g. 01012345678).');
        }
    }
}
