<?php
namespace App\Services;

use App\Exceptions\CompteNotFoundException;
use App\Http\Resources\CompteCollection;
use App\Http\Resources\CompteResource;
use App\Models;
use App\Models\Compte;
use Illuminate\Support\Str;           

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
          $compte = Compte::where('numero_compte', $numero)->first();
          if (!$compte) {
              throw new CompteNotFoundException("Le compte $numero n'existe pas.");
          }
          return new CompteResource($compte);
    }


    public function getCompteBytelephone(string $telephone)
    {
        // Normaliser le numéro de téléphone en supprimant les caractères spéciaux
        $normalizedPhone = preg_replace('/[^0-9]/', '', $telephone);
        
        // Recherche les comptes liés à l'utilisateur avec ce numéro de téléphone
        $comptes = Compte::join('users', 'comptes.client_id', '=', 'users.id')
            ->where('users.telephone', $normalizedPhone)
            ->select('comptes.*')
            ->get();

        if ($comptes->isEmpty()) {
            throw new CompteNotFoundException("Aucun compte trouvé pour le numéro de téléphone $telephone");
        }

        return new CompteCollection($comptes);

    }
public function createCompte(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Vérifier si le client existe
            $client = User::where('email', $data['client']['email'])
                        ->orWhere('telephone', $data['client']['telephone'])
                        ->first();

            // Créer le client si inexistant
            if (!$client) {
                $password = Str::random(8);

                $client = User::create([
                    'nom' => $data['client']['titulaire'],
                    'email' => $data['client']['email'],
                    'telephone' => $data['client']['telephone'],
                    'adresse' => $data['client']['adresse'],
                    'password' => Hash::make($password),
                    'role' => 'client',
                ]);
            }

            // Générer un numéro de compte unique
            

            // Créer le compte avec statut actif par défaut
            $compte = Compte::create([
                'type' => $data['type'],
                'solde' => $data['solde'],
                'devise' => $data['devise'],
                'statut' => 'actif',
                'client_id' => $client->id,
            ]);

            // Préparer la réponse formatée
            return [
                'id' => (string) $compte->id,
                'numeroCompte' => $compte->numero_compte,
                'titulaire' => $client->nom,
                'type' => $compte->type,
                'solde' => $compte->solde,
                'devise' => $compte->devise,
                'dateCreation' => $compte->created_at->toIso8601String(),
                'statut' => $compte->statut,
                'metadata' => [
                    'derniereModification' => $compte->updated_at->toIso8601String(),
                    'version' => 1,
                ],
            ];
        });
    }
    
}