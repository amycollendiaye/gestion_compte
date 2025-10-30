<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

use function Laravel\Prompts\error;

/**
 * @OA\Tag(
 *     name="Authentification",
 *     description="Endpoints pour l'authentification des utilisateurs"
 * )
 */
class AuthController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Post(
     *     path="/ndiaye/api/v1/auth",
     *     summary="Connexion d'un utilisateur",
     *     description="Authentifie un utilisateur et retourne un token JWT",
     *     operationId="login",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="santina.baumbach@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *           
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants invalides",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Identifiants invalides")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors de la création du token")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
    'email' => 'required|email',
    'password' => 'required|string',
]);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                /// A FAIRE AVEC UN TRAIT 
                    return $this->errorResponse("identifiants invalides",401);
                
            }

            $user = Auth::user();
/// A FAIRE AVEC UN TRAIT 
            return $this->successResponse("connexion reussi",[
            'user'=>$user,
            'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => 31000, // en secondes

            ],200);

            
        } catch (JWTException $e) {
                 return  $this->errorResponse("Erreur lors de la création du token",500);
            
        }
    }

    /**
     * @OA\Post(
     *     path="/ndiaye/api/v1/auth/refresh",
     *     summary="Rafraîchir le token JWT",
     *     description="Génère un nouveau token JWT en utilisant le token actuel",
     *     operationId="refreshToken",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token rafraîchi avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Token rafraîchi avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *                 @OA\Property(property="token_type", type="string", example="bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=30)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token invalide ou expiré",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token invalide ou expiré")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors du rafraîchissement du token")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return $this->successResponse("Token rafraîchi avec succès", [
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => 30, // en secondes
            ], 200);
        } catch (JWTException $e) {
            return $this->errorResponse("Token invalide ou expiré", 401);
        } catch (\Exception $e) {
            return $this->errorResponse("Erreur lors du rafraîchissement du token", 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/ndiaye/api/v1/auth/logout",
     *     summary="Déconnexion de l'utilisateur",
     *     description="Invalide le token JWT actuel et déconnecte l'utilisateur",
     *     operationId="logout",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token invalide",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Token invalide")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur lors de la déconnexion")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->successResponse("Déconnexion réussie", null, 200);

        } catch (JWTException $e) {
            return $this->errorResponse("Token invalide", 401);
        } catch (\Exception $e) {
            return $this->errorResponse("Erreur lors de la déconnexion", 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Récupérer les informations de l'utilisateur connecté",
     *     description="Retourne les informations de l'utilisateur actuellement authentifié",
     *     operationId="me",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'utilisateur",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
  
}
