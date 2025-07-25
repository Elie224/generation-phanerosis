<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MainAdminSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté et est un administrateur principal
        if (auth()->check() && auth()->user()->isMainAdmin()) {
            // Logger l'action de l'administrateur principal
            \Log::info('Action de l\'administrateur principal', [
                'action' => $request->method(),
                'url' => $request->fullUrl(),
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);
        }

        return $next($request);
    }
}
