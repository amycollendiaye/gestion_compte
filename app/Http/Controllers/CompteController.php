<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Services\CompteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompteController extends Controller
{
   use ApiResponse;

   protected $compteService;
    public  function __construct(CompteService $compteService)
    {
         $this->compteService=$compteService;
    }
     public function index (Request $request){
      $limit=$request->input('limit',5);
      $page=$request->input('page',1);
      $comptes=$this->compteService->listesComptes($limit,$page);
         return  $this->paginatedResponse($comptes,"liste les comptes demandes");
       
      }
       public function showBynumero($numero){
            // $numero=$request->input('numero');  j'utilse cette approche si  les donnes   passe par le cors de la rquete cesta dire post put ou patch
          if (!$numero) {
          return $this->errorResponse("Le numéro de compte est requis", 422);
    }
          $compte=$this->compteService->getCompteNum($numero);
          if($compte) return $this->successResponse($compte,"rechercheer avec succes",200);
          return $this->errorResponse("Aucun compte trouvé", 404);
       }

     public function showBytelephone($telephone){
    
          $compte= $this->compteService->getCompteByTelephone($telephone);
          if(!$compte) return $this->errorResponse("Le numéro de compte est requis",404);
          return $this->successResponse($compte,'trouve avec succces',200);
    }

}