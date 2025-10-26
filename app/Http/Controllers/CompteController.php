<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Services\CompteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Gestion Compte API",
 *     version="1.0.0",
 *     description="API pour la gestion des comptes bancaires"
 * )
 *
 * @OA\Server(
 *     url="https://amycollendiaye-gestion-compte.onrender.com",
 *     description="Serveur de production"
 * )
 */
class CompteController extends Controller
{
   use ApiResponse;

    protected $compteService;
     public  function __construct(CompteService $compteService)
     {
          $this->compteService=$compteService;
     }

     /**
      * @OA\Get(
      *     path="/ndiaye/api/comptes",
      *     summary="Lister les comptes",
      *     description="Récupère la liste paginée des comptes",
      *     operationId="getComptes",
      *     tags={"Comptes"},
      *     @OA\Parameter(
      *         name="page",
      *         in="query",
      *         description="Numéro de la page",
      *         required=false,
      *         @OA\Schema(type="integer", default=1)
      *     ),
      *     @OA\Parameter(
      *         name="limit",
      *         in="query",
      *         description="Nombre d'éléments par page",
      *         required=false,
      *         @OA\Schema(type="integer", default=5)
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Liste des comptes récupérée avec succès",
      *         @OA\JsonContent(
      *             @OA\Property(property="success", type="boolean", example=true),
      *             @OA\Property(property="message", type="string", example="liste les comptes demandes"),
      *             @OA\Property(
      *                 property="data",
      *                 type="array",
      *                 @OA\Items(
      *                     @OA\Property(property="id", type="string", example="b18818b7-335d-3a8b-9c77-8927c30d6acf"),
      *                     @OA\Property(property="numero", type="string", example="ORANGEBANK-52113367"),
      *                     @OA\Property(property="type", type="string", example="cheque"),
      *                     @OA\Property(property="solde", type="number", format="float", example=0),
      *                     @OA\Property(property="archive", type="string", example="non_supprime"),
      *                     @OA\Property(
      *                         property="client",
      *                         @OA\Property(property="id", type="string", example="d4f29175-dfad-37aa-9e96-37f47dd4f2a2"),
      *                         @OA\Property(property="cni", type="string", example="ML787246"),
      *                         @OA\Property(
      *                             property="user",
      *                             @OA\Property(property="nom", type="string", example="Orin"),
      *                             @OA\Property(property="prenom", type="string", example="Haley"),
      *                             @OA\Property(property="telephone", type="string", example="539.756.2409"),
      *                             @OA\Property(property="email", type="string", example="hilario.sanford@example.net")
      *                         )
      *                     ),
      *                     @OA\Property(property="statut", type="string", example="actif")
      *                 )
      *             ),
      *             @OA\Property(
      *                 property="metadonnees",
      *                 @OA\Property(property="date_creation", type="string", format="date-time", example="2025-10-26T19:51:55+00:00"),
      *                 @OA\Property(property="version", type="integer", example=1)
      *             ),
      *             @OA\Property(
      *                 property="pagination",
      *                 @OA\Property(property="currentPage", type="integer", example=1),
      *                 @OA\Property(property="totalPages", type="integer", example=1),
      *                 @OA\Property(property="totalItems", type="integer", example=4),
      *                 @OA\Property(property="itemsPerPage", type="integer", example=5),
      *                 @OA\Property(property="hasNext", type="boolean", example=false),
      *                 @OA\Property(property="hasPrevious", type="boolean", example=false)
      *             ),
      *             @OA\Property(
      *                 property="links",
      *                 @OA\Property(property="self", type="string", example="http://localhost:8000/ndiaye/api/comptes?page=1"),
      *                 @OA\Property(property="next", type="string", nullable=true, example=null),
      *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
      *                 @OA\Property(property="first", type="string", example="http://localhost:8000/ndiaye/api/comptes?page=1"),
      *                 @OA\Property(property="last", type="string", example="http://localhost:8000/ndiaye/api/comptes?page=1")
      *             )
      *         )
      *     )
      * )
      */
      public function index (Request $request){
       $limit=$request->input('limit',5);
       $page=$request->input('page',1);
       $comptes=$this->compteService->listesComptes($limit,$page);
          return  $this->paginatedResponse($comptes,"liste les comptes demandes");

       }

       /**
        * @OA\Get(
        *     path="/ndiaye/api/comptes/{numero}",
        *     summary="Récupérer un compte par numéro",
        *     description="Récupère les détails d'un compte spécifique par son numéro",
        *     operationId="getCompteByNumero",
        *     tags={"Comptes"},
        *     @OA\Parameter(
        *         name="numero",
        *         in="path",
        *         description="Numéro du compte",
        *         required=true,
        *         @OA\Schema(type="string")
        *     ),
        *     @OA\Response(
        *         response=200,
        *         description="Compte trouvé avec succès",
        *         @OA\JsonContent(
        *             @OA\Property(property="success", type="boolean", example=true),
        *             @OA\Property(property="message", type="string", example="rechercheer avec succes"),
        *             @OA\Property(
        *                 property="data",
        *                 @OA\Property(property="id", type="string", example="b18818b7-335d-3a8b-9c77-8927c30d6acf"),
        *                 @OA\Property(property="numero", type="string", example="ORANGEBANK-52113367"),
        *                 @OA\Property(property="type", type="string", example="cheque"),
        *                 @OA\Property(property="solde", type="number", format="float", example=0),
        *                 @OA\Property(property="archive", type="string", example="non_supprime"),
        *                 @OA\Property(
        *                     property="client",
        *                     @OA\Property(property="id", type="string", example="d4f29175-dfad-37aa-9e96-37f47dd4f2a2"),
        *                     @OA\Property(property="cni", type="string", example="ML787246"),
        *                     @OA\Property(
        *                         property="user",
        *                         @OA\Property(property="nom", type="string", example="Orin"),
        *                         @OA\Property(property="prenom", type="string", example="Haley"),
        *                         @OA\Property(property="telephone", type="string", example="539.756.2409"),
        *                         @OA\Property(property="email", type="string", example="hilario.sanford@example.net")
        *                     )
        *                 ),
        *                 @OA\Property(property="statut", type="string", example="actif")
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Aucun compte trouvé",
        *         @OA\JsonContent(
        *             @OA\Property(property="success", type="boolean", example=false),
        *             @OA\Property(property="message", type="string", example="Aucun compte trouvé"),
        *             @OA\Property(property="errors", type="object", nullable=true)
        *         )
        *     )
        * )
        */
        public function showBynumero($numero){
             // $numero=$request->input('numero');  j'utilse cette approche si  les donnes   passe par le cors de la rquete cesta dire post put ou patch
           if (!$numero) {
           return $this->errorResponse("Le numéro de compte est requis", 422);
     }
           $compte=$this->compteService->getCompteNum($numero);
           if($compte) return $this->successResponse("rechercheer avec succes", $compte, 200);
           return $this->errorResponse("Aucun compte trouvé", 404);
        }

        /**
         * @OA\Get(
         *     path="/ndiaye/api/comptes/telephone/{telephone}",
         *     summary="Récupérer un compte par téléphone",
         *     description="Récupère les détails d'un compte spécifique par le téléphone du client",
         *     operationId="getCompteByTelephone",
         *     tags={"Comptes"},
         *     @OA\Parameter(
         *         name="telephone",
         *         in="path",
         *         description="Numéro de téléphone du client",
         *         required=true,
         *         @OA\Schema(type="string")
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Compte trouvé avec succès",
         *         @OA\JsonContent(
         *             @OA\Property(property="success", type="boolean", example=true),
         *             @OA\Property(property="message", type="string", example="trouve avec succces"),
         *             @OA\Property(
        *                 property="data",
        *                 @OA\Property(property="id", type="string", example="b18818b7-335d-3a8b-9c77-8927c30d6acf"),
        *                 @OA\Property(property="numero", type="string", example="ORANGEBANK-52113367"),
        *                 @OA\Property(property="type", type="string", example="cheque"),
        *                 @OA\Property(property="solde", type="number", format="float", example=0),
        *                 @OA\Property(property="archive", type="string", example="non_supprime"),
        *                 @OA\Property(
        *                     property="client",
        *                     @OA\Property(property="id", type="string", example="d4f29175-dfad-37aa-9e96-37f47dd4f2a2"),
        *                     @OA\Property(property="cni", type="string", example="ML787246"),
        *                     @OA\Property(
        *                         property="user",
        *                         @OA\Property(property="nom", type="string", example="Orin"),
        *                         @OA\Property(property="prenom", type="string", example="Haley"),
        *                         @OA\Property(property="telephone", type="string", example="539.756.2409"),
        *                         @OA\Property(property="email", type="string", example="hilario.sanford@example.net")
        *                     )
        *                 ),
        *                 @OA\Property(property="statut", type="string", example="actif")
        *             )
        *         )
        *     ),
        *     @OA\Response(
        *         response=404,
        *         description="Aucun compte trouvé",
        *         @OA\JsonContent(
        *             @OA\Property(property="success", type="boolean", example=false),
        *             @OA\Property(property="message", type="string", example="Le numéro de compte est requis"),
        *             @OA\Property(property="errors", type="object", nullable=true)
        *         )
        *     )
        * )
         */
      public function showBytelephone($telephone){

           $compte= $this->compteService->getCompteByTelephone($telephone);
           if(!$compte) return $this->errorResponse("Le numéro de compte est requis",404);
           return $this->successResponse('trouve avec succces', $compte, 200);
     }

}