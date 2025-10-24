<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\CompteValidation;
use App\Models\Compte;
use App\Models\Client;

class CompteController extends Controller
{
    /**
     * Valider les données d'un compte.
     */
    public function validateCompte(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_compte' => ['nullable', 'string', new CompteValidation()],
            'type_compte' => ['required', 'string', new CompteValidation()],
            'statut' => ['required', 'string', new CompteValidation()],
            'archive' => ['required', 'string', new CompteValidation()],
            'client_id' => ['required', 'string', 'exists:clients,id', new CompteValidation()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Si la validation passe, retourner les données validées
        return response()->json([
            'success' => true,
            'message' => 'Données du compte valides.',
            'data' => $validator->validated()
        ]);
    }

    /**
     * Créer un compte après validation.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_compte' => ['nullable', 'string', new CompteValidation()],
            'type_compte' => ['required', 'string', new CompteValidation()],
            'statut' => ['required', 'string', new CompteValidation()],
            'archive' => ['required', 'string', new CompteValidation()],
            'client_id' => ['required', 'string', 'exists:clients,id', new CompteValidation()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $compte = Compte::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Compte créé avec succès.',
            'data' => $compte
        ], 201);
    }
}