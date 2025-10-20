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
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('can:ventas.index')->only(['index']);
    $this->middleware('can:ventas.create')->only(['create', 'store']);
    $this->middleware('can:ventas.show')->only(['show', 'ticket']);
    $this->middleware('can:ventas.destroy')->only(['destroy']);
    $this->middleware('can:ventas.reportes')->only(['reporteVentas']);
}
}
