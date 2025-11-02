<?php

namespace App\Http\Requests;

use App\Rules\Email;
use App\Rules\Telephone;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulaire' => 'sometimes|string|max:255',
            'informationsClient.telephone' => [
                'sometimes',
                'nullable',
                new Telephone(),
                'unique:users,telephone'
            ],
            'informationsClient.email' => [
                'sometimes',
                'nullable',
                new Email(),
                'unique:users,email'
            ],
            'informationsClient.password' => 'sometimes|nullable|string|min:8',
            'informationsClient.nci' => 'sometimes|nullable|string|max:255|unique:clients,cni',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titulaire.string' => 'Le titulaire doit être une chaîne de caractères.',
            'titulaire.max' => 'Le titulaire ne peut pas dépasser 255 caractères.',
            'informationsClient.telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'informationsClient.email.unique' => 'Cet email est déjà utilisé.',
            'informationsClient.password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'informationsClient.nci.unique' => 'Ce numéro CNI est déjà utilisé.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $input = $validator->getData();

            // Vérifier qu'au moins un champ est fourni
            $hasTitulaire = !empty($input['titulaire']);
            $hasTelephone = !empty($input['informationsClient']['telephone']);
            $hasEmail = !empty($input['informationsClient']['email']);
            $hasPassword = !empty($input['informationsClient']['password']);
            $hasNci = !empty($input['informationsClient']['nci']);

            if (!$hasTitulaire && !$hasTelephone && !$hasEmail && !$hasPassword && !$hasNci) {
                $validator->errors()->add('general', 'Au moins un champ doit être fourni pour la mise à jour.');
            }
        });
    }
}