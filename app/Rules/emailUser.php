<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class emailUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
            $pattern = '/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/';

        if (!preg_match($pattern, $value)) {
            $fail("Le champ $attribute n'a pas un format email valide.");
        }
    }
    }

