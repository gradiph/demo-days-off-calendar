<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UniqueOffDay implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        $isExist = $user->offdays()
            ->where('date', $value)
            ->exists();
        if ($isExist) {
            $fail('The :attribute has already been taken.');
        }
    }
}
