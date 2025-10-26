<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class archiveCompte implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
     protected  array $types;
    public  function __construct(array $types=["supprime",'nonsupprime','bloque'])
    {
         $this->types=$types;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(strtolower($value), $this->types)) {
            $fail("Le $attribute doit être l’un des types suivants : " . implode(', ', $this->types) . ".");
        }
    }
}
