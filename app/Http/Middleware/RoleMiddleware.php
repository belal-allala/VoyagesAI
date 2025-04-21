<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role // Le rôle requis comme argument
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) { 
            return response('Unauthorized', 401); 
        }

        if ($request->user()->hasRole($role)) {
            return $next($request);
        }

        return response('Accès interdit.', 403); 
    }
}