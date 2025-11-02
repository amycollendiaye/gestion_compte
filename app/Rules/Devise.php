<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Devise implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
     protected  array $types;
      public  function __construct(array $types=['XOF','DOLLARS','EUROS'])
      {
         $this->types=$types;
      }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(strtoupper($value), array_map('strtoupper', $this->types))) {
            $fail("Le $attribute doit être l'un des types suivants : " . implode(', ', $this->types) . ".");
        }
    }
}
