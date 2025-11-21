<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class RoleMiddleware
{
    public function handle($request, Closure $next, $roleName)
    {
        // Primero intenta por claim del token
        $roleId = $request->attributes->get('jwt_role_id');

        if ($roleId) {
            // Si tienes tabla roles:
            $rol = DB::table('roles')->where('id', $roleId)->first();
            if (!$rol || strcasecmp($rol->nombre, $roleName) !== 0) {
                return response()->json(['error' => 'Permisos insuficientes'], 403);
            }
        } else {
            // fallback: por el modelo (si el user tiene rol_id)
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            // compara por nombre desde tabla
            $rol = DB::table('roles')->where('id', $user->rol_id)->first();
            if (!$rol || strcasecmp($rol->nombre, $roleName) !== 0) {
                return response()->json(['error' => 'Permisos insuficientes'], 403);
            }
        }

        return $next($request);
    }
}
