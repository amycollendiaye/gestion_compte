<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero_compte,
            'type' => $this->type_compte,
            'solde' => (float) $this->solde,
            "archive"=>$this->archive,
            'client' => $this->client ? [
                'id' => $this->client->id,
                'cni' => $this->client->cni,
                // Vérifie que le user existe (relation client → user)
                
                    'nom' => $this->client->user->nom,
                    'prenom' => $this->client->user->prenom,
                    'telephone' => $this->client->user->telephone,
                    'email' => $this->client->user->email,
                    'role'=>$this->client->user->role,
                
            ] : null,
            'statut' => $this->statut,
        //          'links' => [
        //     'self' => route('comptes.showBytelephone', $this->numero_compte),
        //     'client' => route('clients.showBytelephone', $this->client_id),
        // ],
          
               
               
            
        ];
    }
}
