<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class JwtRoleMiddleware
{
    public function handle($request, Closure $next, $roleName)
    {
        // 1) Primero intenta leer el rol desde el claim que puso tu JwtMiddleware
        $roleId = $request->attributes->get('jwt_role_id');

        if ($roleId) {
            // Si tienes tabla roles con columna 'nombre'
            $rol = DB::table('roles')->where('id', $roleId)->first();
            if (!$rol || strcasecmp($rol->nombre, $roleName) !== 0) {
                return response()->json(['error' => 'Permisos insuficientes'], 403);
            }
        } else {
            // 2) Fallback: desde el usuario cargado por $request->user()
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            $rol = DB::table('roles')->where('id', $user->rol_id)->first();
            if (!$rol || strcasecmp($rol->nombre, $roleName) !== 0) {
                return response()->json(['error' => 'Permisos insuficientes'], 403);
            }
        }

        return $next($request);
    }
}
