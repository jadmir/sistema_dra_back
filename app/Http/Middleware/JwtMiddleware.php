<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $jwtKey = env('JWT_SECRET');

        try {
            $authHeader = $request->header('Authorization');
            if(!$authHeader) {
                return response()->json(['error' => 'Token no proporcionado'], 401);
            }

            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = JWT::decode($token, new Key($jwtKey, 'HS256'));

            //Compartir el user_id con las respuestas
            $request->attributes->add(['user_id' => $decoded->sub]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token inválido o experido',
                'detalle' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
