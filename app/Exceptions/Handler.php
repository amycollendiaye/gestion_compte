<?php

namespace App\Exceptions;
use App\Exceptions\CompteNotFoundException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


public function render($request, Throwable $exception)
{
    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        return response()->json([
            'success' => false,
            'message' => 'Accès non autorisé!!! coach!. Token manquant ou invalide.',
        ], 401);
    }

    if ($exception instanceof JWTException) {
        return response()->json([
            'success' => false,
            'message' => 'Token invalide ou expiré. Veuillez vous reconnecter.',
        ], 401);
    }

    // Garder le code existant pour CompteNotFoundException
    if ($exception instanceof CompteNotFoundException) {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
        ], $exception->getCode());
    }

    return parent::render($request, $exception);
}


}
