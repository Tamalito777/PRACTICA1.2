<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
{
    if (Auth::check() && Auth::user()->rol === $role) {
        return $next($request);
    }

    abort(403, 'No tienes autorización para acceder a esta sección.');
}
}
