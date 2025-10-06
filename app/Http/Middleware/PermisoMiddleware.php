<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermisoMiddleware
{
    /**
     * Maneja una solicitud entrante verificando permisos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permisoNombre
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permisoNombre): Response
    {
        $user = $request->user();

        // Validar si el usuario está autenticado y tiene rol
        if (!$user || !$user->rol) {
            return response()->json([
                'message' => 'Usuario no autenticado o sin rol asignado.'
            ], 403);
        }

        // Verificar que el rol tenga la relación permisos
        if (!$user->rol->relationLoaded('permisos')) {
            $user->rol->load('permisos');
        }

        // Validar que el rol tenga el permiso solicitado
        $tienePermiso = $user->rol->permisos->contains('nombre', $permisoNombre);

        if (!$tienePermiso) {
            return response()->json([
                'message' => 'No tienes permiso para acceder a este recurso.'
            ], 403);
        }

        // ✅ Si pasa todas las validaciones, continúa
        return $next($request);
    }
}
