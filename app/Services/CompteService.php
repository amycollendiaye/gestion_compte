<?php
namespace App\Services;

use App\Exceptions\CompteNotFoundException;
use App\Http\Resources\CompteCollection;
use App\Http\Resources\CompteResource;
use App\Models\Client;
use App\Models\Compte;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompteService{
    public function  listesComptes($limit=10,$page=1 , $type=null){
            $query = Compte::query()
            // avec le scope   global que  jai cplus besion de mettre
        // ->where('archive', '!=', 'supprime')
        ->whereIn('statut', ['actif'])
        ->whereIn('type_compte', ['epargne', 'cheque', 'courant'])
        ->orderBy('dateCreation', 'desc');

            if ($type) {
            $query->where('type_compte', $type);
        }
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
        
        // Recherche les comptes liés à l'utilisateur avec ce numéro de téléphone
        $comptes = Compte::join('users', 'comptes.client_id', '=', 'users.id')
            ->where('users.telephone', $telephone)
            ->select('comptes.*')
            ->get();

        if ($comptes->isEmpty()) {
            throw new CompteNotFoundException("Aucun compte trouvé pour le nufméro de téléphone $telephone");
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

                // Créer l'utilisateur
                $user = User::create([
                    'nom' => $data['client']['titulaire'],
                    'prenom' => $data['client']['prenom'],
                    'email' => $data['client']['email'],
                    'telephone' => $data['client']['telephone'],
                    'adresse' => $data['client']['adresse'],
                    'password' => Hash::make($password),
                    'role' => 'client',
                ]);

                // Créer le client associé à l'utilisateur
                $client = Client::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'cni' => 'CNI' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT)
                ]);
            } else {
                // Si l'utilisateur existe, récupérer le client associé
                $client = Client::where('user_id', $client->id)->firstOrFail();
            }

            // Générer un numéro de compte unique
            

            // Créer le compte avec statut actif par défaut
            $compte = new Compte();
            $compte->id = (string) Str::uuid();
            $compte->numero_compte = ''; // Le mutator générera automatiquement le numéro
            $compte->type_compte = $data['type'];
            $compte->solde = $data['solde'];
            $compte->devise = $data['devise'];
            $compte->statut = 'actif';
            $compte->client_id = $client->id;
            $compte->archive = 'non_supprime';
            $compte->save();

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
      public function getCommptesById(string $id){
                $comptes=Compte::where('client_id',$id)->get();
                 if (!$comptes) {
              throw new CompteNotFoundException("Le compte $id n'existe pas.");
          }
          return  new CompteCollection($comptes);
      }
    }
         
