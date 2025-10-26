<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class passwordUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
                if (strlen($value) > 6) {
            $fail("Le champ $attribute ne doit pas dépasser 6 caractères.");
        }

        // Optionnel : vérifier qu'il n'est pas vide
        if (strlen($value) < 1) {
            $fail("Le champ $attribute doit contenir au moins 1 caractère.");
        }

    }
}
