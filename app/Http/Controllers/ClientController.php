<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    use ApiResponse;

    /**
     * Mettre à jour les informations d'un client.
     */
    public function update(UpdateClientRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Trouver le client
            $client = Client::findOrFail($id);
            $user = $client->user;

            if (!$user) {
                return $this->errorResponse('Utilisateur associé au client non trouvé.', 404);
            }

            $data = $request->validated();

            // Mettre à jour le titulaire si fourni
            if (isset($data['titulaire'])) {
                $user->nom = $data['titulaire'];
                $user->save();
            }

            // Mettre à jour les informations client si fournies
            $infoClient = $data['informationsClient'] ?? [];

            if (isset($infoClient['telephone'])) {
                $user->telephone = $infoClient['telephone'];
            }

            if (isset($infoClient['email'])) {
                $user->email = $infoClient['email'];
            }

            if (isset($infoClient['password'])) {
                $user->password = Hash::make($infoClient['password']);
            }

            if (isset($infoClient['nci'])) {
                $client->cni = $infoClient['nci'];
            }

            $user->save();
            $client->save();

            DB::commit();

            // Récupérer le compte principal du client pour la réponse
            $compte = $client->comptes()->first();

            if (!$compte) {
                return $this->errorResponse('Aucun compte trouvé pour ce client.', 404);
            }

            $responseData = [
                'id' => $compte->id,
                'numeroCompte' => $compte->numero_compte,
                'titulaire' => $user->nom,
                'type' => $compte->type,
                'solde' => $compte->solde,
                'devise' => $compte->devise,
                'statut' => $compte->statut,
                'client' => [
                    'id' => $client->id,
                    'telephone' => $user->telephone,
                    'email' => $user->email,
                    'nci' => $client->cni,
                ]
            ];

            return $this->successResponse('Informations du client mises à jour avec succès', $responseData);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return $this->errorResponse('Client non trouvé.', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Erreur lors de la mise à jour du client.', 500);
        }
    }
}
