<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $jwtKey = env('JWT_SECRET');

        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $token = substr($authHeader, 7);
            $decoded = JWT::decode($token, new Key($jwtKey, 'HS256'));

            // Asegura que sea un access token
            if (($decoded->type ?? 'access') !== 'access') {
                return response()->json(['error' => 'Token inválido (no es access)'], 401);
            }

            // Carga usuario
            $user = \App\Models\Usuario::find($decoded->sub ?? null);
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 401);
            }

            // Inyecta user resolver (para $request->user())
            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            // Guarda claims útiles
            $request->attributes->set('jwt_user_id', $decoded->sub ?? null);
            $request->attributes->set('jwt_role_id', $decoded->rol ?? null);

            // Fuerza JSON (evita redirects a /login)
            $request->headers->set('Accept', 'application/json');

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Token inválido o expirado',
                'detalle' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}
