<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompteRequest;
use App\Models\Compte;
use App\Services\CompteService;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;



/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="Gestion des comptes bancaires wane"
 * )
 * 
 * 
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
    *     path="/ndiaye/api/v1/comptes",
    *     summary="Lister les comptes",
    *     description="Récupère une liste paginée des comptes bancaires",
    *     operationId="listComptes",
    *     tags={"Comptes"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="limit",
    *         in="query",
    *         description="Nombre d'éléments par page",
    *         required=false,
    *         @OA\Schema(type="integer", default=5, minimum=1, maximum=100)
    *     ),
    *     @OA\Parameter(
    *         name="page",
    *         in="query",
    *         description="Numéro de la page",
    *         required=false,
    *         @OA\Schema(type="integer", default=1, minimum=1)
    *     ),
    *     @OA\Parameter(
    *         name="type",
    *         in="query",
    *         description="Type de compte (courant, epargne, cheque)",
    *         required=false,
    *         @OA\Schema(type="string", enum={"courant", "epargne", "cheque"})
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Liste des comptes récupérée avec succès",
    *         @OA\JsonContent(
    *
    *
    *                     )
    *                 ),
    *
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Erreur serveur",
    *         @OA\JsonContent(
    *
    *         )
    *     )
    * )
    */
      public function index (Request $request){
       $limit=$request->input('limit',5);
       $page=$request->input('page',1);
       $type=$request->input('type');
            $user = Auth::user();
       if($user->isAdmin()){

            $comptes=$this->compteService->listesComptes($limit,$page,$type);
           return  $this->paginatedResponse($comptes,"liste les comptes demandes");
       }
       elseif ($user->isClient())
       {
           $id=$request->input('id');
           $comptes=$this->compteService->getCommptesById($id);

           return  $this->paginatedResponse($comptes,"liste les comptes demandes");
  }
   else {
           return $this->errorResponse('Accès refusé',403);
   }

     }

   /**
    * @OA\Get(
    *     path="/ndiaye/api/v1/comptes/{numero}",
    *     summary="Rechercher un compte par numéro",
    *     description="Récupère les détails d'un compte bancaire spécifique par son numéro",
    *     operationId="getCompteByNumero",
    *     tags={"Comptes"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="numero",
    *         in="path",
    *         description="Numéro du compte bancaire",
    *         required=true,
    *
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Compte trouvé avec succès",
    *         @OA\JsonContent(
    *
    *
    *
    *
    *         )
    *     ),
    *
    *     )
    * )
    */
        public function showBynumero($numero){
             // $numero=$request->input('numero');  j'utilse cette approche si  les donnes   passe par le cors de la rquete cesta dire post put ou patch
           if (!$numero) {
           return $this->errorResponse("Le numéro de compte est requis", 422);
     }
           $compte=$this->compteService->getCompteNum($numero);
           if($compte) return $this->successResponse("rechercheer avec succes",$compte,200);
           return $this->errorResponse("Aucun compte trouvé", 404);
        }


   /**
    * @OA\Get(
    *     path="/ndiaye/api/v1/comptes/telephone/{telephone}",
    *     summary="Rechercher un compte par téléphone",
    *     description="Récupère les détails d'un compte bancaire par le numéro de téléphone du client",
    *     operationId="getCompteByTelephone",
    *     tags={"Comptes"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="telephone",
    *         in="path",
    *         description="Numéro de téléphone du client",
    *         required=true,
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Compte trouvé avec succès",
    *
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Aucun compte trouvé",
    *         @OA\JsonContent(
    *
    *         )
    *     )
    * )
    */
      public function showBytelephone($telephone){

           $compte= $this->compteService->getCompteByTelephone($telephone);
           if(!$compte) return $this->errorResponse("Le numéro de compte est requis",404);
           return $this->successResponse('trouve avec succces',$compte,200);
     }

   /**
    * @OA\Post(
    *     path="/ndiaye/api/v1/comptes",
    *     summary="Créer un nouveau compte",
    *     description="Crée un nouveau compte bancaire avec les informations du client",
    *     operationId="createCompte",
    *     tags={"Comptes"},
    *     security={{"bearerAuth":{}}},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Compte créé avec succès",
    *         @OA\JsonContent(
    *
    *                 )
    *
    *
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Données invalides",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Les données fournies sont invalides"),
    *             @OA\Property(property="error", type="object",
    *                 @OA\Property(property="code", type="string", example="VALIDATION_ERROR"),
    *                 @OA\Property(property="details", type="object")
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Erreur serveur",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="SERVER_ERROR")
    *         )
    *     )
    * )
    */
public function store(CreateCompteRequest $request)
{
    try {
        $compte = $this->compteService->createCompte($request->validated());
           return $this->successResponse('Compte créé avec succès',$compte,200);

        


    } catch (\Illuminate\Validation\ValidationException $e) {
 /// je doit  modofier les formatde response dans le trait apiresponse a faire aavant de push
        return  $this->errorResponse("Les données fournies sont invalides",422, ['error' => [
                'code' => 'VALIDATION_ERROR',
                'message' => '',
                'details' => $e->errors(),
            ]]);

    } catch (\Exception $e) {

        return  $this->errorResponse("SERVER_ERROR",500,['message' => $e->getMessage()]);
        
    
    }
}
   /**
    * @OA\Delete(
    *     path="/ndiaye/api/v1/comptes/{numero}",
    *     summary="Supprimer un compte (soft delete)",
    *     description="Marque un compte bancaire comme supprimé sans le supprimer définitivement",
    *     operationId="deleteCompte",
    *     tags={"Comptes"},
    *     security={{"bearerAuth":{}}},
    *     @OA\Parameter(
    *         name="numero",
    *         in="path",
    *         description="Numéro du compte bancaire à supprimer",
    *         required=true,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Compte supprimé avec succès",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="message", type="string", example="Compte supprimé (soft delete) avec succès"),
    *             @OA\Property(property="data", type="object")
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Compte non trouvé",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="Aucun compte trouvé")
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Erreur serveur",
    *         @OA\JsonContent(
    *             @OA\Property(property="success", type="boolean", example=false),
    *             @OA\Property(property="message", type="string", example="SERVER_ERROR")
    *         )
    *     )
    * )
    */
public function destroy(string $compte){

     $compteObj= $this->compteService->delete($compte);
           $compteObj->delete();


     return   $this->successResponse("Compte supprimé (soft delete) avec succès", $compteObj, 200);

}
}