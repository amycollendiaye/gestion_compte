<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Compte;

class CompteValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validation pour numero_compte : doit commencer par ORANGEBANK- suivi de 10 chiffres
        if ($attribute === 'numero_compte') {
            if (!preg_match('/^ORANGEBANK-\d{10}$/', $value)) {
                $fail("Le $attribute doit commencer par ORANGEBANK- suivi de 10 chiffres.");
                return;
            }
            // Vérifier l'unicité
            if (Compte::where('numero_compte', $value)->exists()) {
                $fail("Le $attribute existe déjà.");
            }
        }

        // Validation pour type_compte : doit être dans la liste enum
        if ($attribute === 'type_compte') {
            $validTypes = ['epargne', 'cheque', 'courant'];
            if (!in_array($value, $validTypes)) {
                $fail("Le $attribute doit être l'un des suivants : " . implode(', ', $validTypes));
            }
        }

        // Validation pour statut : doit être dans la liste enum
        if ($attribute === 'statut') {
            $validStatuses = ['actif', 'inactif', 'bloque'];
            if (!in_array($value, $validStatuses)) {
                $fail("Le $attribute doit être l'un des suivants : " . implode(', ', $validStatuses));
            }
        }

        // Validation pour archive : doit être dans la liste enum
        if ($attribute === 'archive') {
            $validArchives = ['supprime', 'non_supprime'];
            if (!in_array($value, $validArchives)) {
                $fail("Le $attribute doit être l'un des suivants : " . implode(', ', $validArchives));
            }
        }

        // Validation pour client_id : doit être un UUID valide et exister dans clients
        if ($attribute === 'client_id') {
            if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $value)) {
                $fail("Le $attribute doit être un UUID valide.");
                return;
            }
            // Ici, on pourrait vérifier si le client existe, mais pour la validation de données,
            // on peut le faire dans le contrôleur ou utiliser une règle exists
        }
    }
}