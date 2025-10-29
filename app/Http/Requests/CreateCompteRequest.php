<?php

namespace App\Http\Requests;

use App\Rules\Devise;
use App\Rules\Email;
use App\Rules\Password;
use App\Rules\Soldecompte;
use App\Rules\Telephone;
use App\Rules\TypeCompte;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompteRequest extends FormRequest
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
            'devise' => ['required', 'string', new Devise()],
            'solde' => ['required', 'numeric', new Soldecompte()],
            'type' => ['required', 'string', new TypeCompte()],

            // client.* fields (dot notation required by Laravel)
            'client.titulaire' => ['required', 'string'],
            'client.prenom' => ['required', 'string'],
            'client.email' => ['required', 'email', 'unique:users,email', new Email()],
            'client.adresse' => ['required', 'string'],
            'client.telephone' => ['required', 'string', new Telephone()],

            

        ];
    }
  public function messages(): array
    {
        return [
            'client.email.unique' => 'Cet email existe déjà.',
            'client.telephone.unique' => 'Ce numéro de téléphone existe déjà.',
            'solde.required' => 'Le solde initial est obligatoire.',
        ];
    }}
