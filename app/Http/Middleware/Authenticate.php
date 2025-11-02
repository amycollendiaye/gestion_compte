<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Pour les routes API, ne pas rediriger, laisser l'exception se produire
        if ($request->is('*/api/*')) {
            return null;
        }

        return $request->expectsJson() ? null : route('login');
    }
}
