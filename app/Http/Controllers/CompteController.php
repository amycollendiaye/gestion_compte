<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\CompteValidation;
use App\Models\Compte;
use App\Models\Client;
use GuzzleHttp\Psr7\Response;

/**
 * @OA\Info(
 *     title="Gestion Compte API",
 *     version="1.0.0",
 *     description="API pour la gestion des comptes"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur principal"
 * )
 */
class CompteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/comptes/validate",
     *     summary="Valider les données d'un compte",
     *     tags={"Comptes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="numero_compte", type="string", nullable=true),
     *             @OA\Property(property="type_compte", type="string", example="courant"),
     *             @OA\Property(property="statut", type="string", example="actif"),
     *             @OA\Property(property="archive", type="string", example="non"),
     *             @OA\Property(property="client_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Validation réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Données du compte valides."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/comptes",
     *     summary="Créer un compte",
     *     tags={"Comptes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="numero_compte", type="string", nullable=true),
     *             @OA\Property(property="type_compte", type="string", example="courant"),
     *             @OA\Property(property="statut", type="string", example="actif"),
     *             @OA\Property(property="archive", type="string", example="non"),
     *             @OA\Property(property="client_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compte créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Compte créé avec succès."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
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
    /**
     * @OA\Get(
     *     path="/api/comptes",
     *     summary="Lister tous les comptes",
     *     tags={"Comptes"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="liste les comptes"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string", example="a26341aa-bbf1-3107-ab48-c9455fc181a7"),
     *                 @OA\Property(property="numero_compte", type="string", example="ORANGEBANK-23994273"),
     *                 @OA\Property(property="type_compte", type="string", example="cheque"),
     *                 @OA\Property(property="statut", type="string", example="inactif"),
     *                 @OA\Property(property="client_id", type="string", example="397c23b1-973d-36d0-8f06-4750fa8b2b56"),
     *                 @OA\Property(property="archive", type="string", example="supprime"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-23T20:00:08.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-23T20:00:08.000000Z")
     *             ))
     *         )
     *     )
     * )
     */
    public function index (Response $response){
         return  response()->json([
             'success'=>true,
             'message'=> 'liste les comptes',
             'data'=> Compte::all()
         ]);

    }
}