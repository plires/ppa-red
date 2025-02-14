<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado y es administrador
        if (Auth::check() && Auth::user()->role === User::ADMIN_USER) {
            return $next($request);
        }

        // Si no es admin, redirigir al dashboard o a la página principal
        return redirect('/')->with('error', 'No tienes permisos para acceder a esta página.');
    }
}
