<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $operation = null)
    {
        $response = $next($request); // exécute la requête

        // On log pour les méthodes POST et PUT
        if ($request->isMethod('post') || $request->isMethod('put')) {

            $data = [
                'datetime' => now()->toDateTimeString(),
                'host' => $request->ip(),
                'operation' => $operation ?? ($request->isMethod('post') ? 'create' : 'update'),
                'resource' => $request->path(),
                'user_agent' => $request->userAgent(),
            ];

            // Log dans storage/logs/laravel.log
            Log::info('Operation log:', $data);
        }

        return $response;
    }
}
