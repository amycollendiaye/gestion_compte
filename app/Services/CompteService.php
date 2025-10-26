<?php
namespace App\Services;

use App\Exceptions\CompteNotFoundException;
use App\Http\Resources\CompteCollection;
use App\Http\Resources\CompteResource;
use App\Models;
use App\Models\Compte;

class CompteService{
    public function  listesComptes($limit=10,$page=1){
            $query = Compte::query()
            // avec le scope   global que  jai cplus besion de mettre
        // ->where('archive', '!=', 'supprime')
        ->whereIn('statut', ['actif'])
        ->whereIn('type_compte', ['epargne', 'cheque', 'courant'])
        ->orderBy('dateCreation', 'desc');


            $comptes= $query->paginate($limit, ['*'], 'page', $page);
            return new  CompteCollection($comptes);


    }
    public function getCompteNum(string $numero){
          $compte=Compte::numero($numero)->first();
          if (!$compte) {
        throw new CompteNotFoundException("Le compte $numero n'existe pas.");
    }
           return new CompteResource( $compte);
    }


    public function getCompteBytelephone(string $telephone)
{
    $comptes = Compte::telephone($telephone)->get();
        if (!$comptes) {
        throw new CompteNotFoundException("Le  $telephone de ce client  n'existe pas. desolle!!!!!");
    }
    return new CompteCollection(($comptes));

        
    }
}

    
