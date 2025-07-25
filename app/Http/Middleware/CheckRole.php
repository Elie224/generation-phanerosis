<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            abort(403, 'Accès non autorisé.');
        }

        $user = $request->user();
        
        // Vérifier si l'utilisateur a un des rôles requis
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Les admins et pasteurs ont accès à tout
        if ($user->isAdmin() || $user->isPastor()) {
            return $next($request);
        }

        abort(403, 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
