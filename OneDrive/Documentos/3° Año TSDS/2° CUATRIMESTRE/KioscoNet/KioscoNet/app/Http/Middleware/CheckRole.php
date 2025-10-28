<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Verifica que el usuario autenticado tenga el rol requerido
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $rol  El rol requerido (administrador o vendedor)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }

        // Verificar si el usuario tiene el rol requerido
        if (auth()->user()->rol !== $rol) {
            abort(403, 'No tienes permisos suficientes para acceder a esta sección.');
        }

        return $next($request);
    }
}
