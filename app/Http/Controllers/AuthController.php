<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $jwtKey;
    private $ttl;
    private $refreshTtl;

    public function __construct()
    {
        $this->jwtKey = env('JWT_SECRET');
        $this->ttl = (int) env('JWT_TTL', 7200); // 2 horas
        $this->refreshTtl = (int) env('JWT_REFRESH_TTL', 604800); // 7 días
    }

    private function generateToken($userId, $rolId, $isRefresh = false)
    {
        $now = Carbon::now()->timestamp;
        $exp = $now + ($isRefresh ? $this->refreshTtl : $this->ttl);

        $payload = [
            'iss' => 'sistema-dra',
            'sub' => $userId,
            'rol' => $rolId,
            'iat' => $now,
            'exp' => $exp,
            'type' => $isRefresh ? 'refresh' : 'access',
        ];

        return JWT::encode($payload, $this->jwtKey, 'HS256');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = DB::table('usuarios')->where('email', $request->email)->first();

        if(!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $accessToken = $this->generateToken($usuario->id, $usuario->rol_id);
        $refreshToken = $this->generateToken($usuario->id, $usuario->rol_id, true);

        return response()->json([
            'message' => 'Login exitoso',
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'usuario' => [
                'id' => $usuario->id,
                'email' => $usuario->email,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'rol_id' => $usuario->rol_id,
            ],
        ]);
    }

    public function perfil(Request $request)
    {
        $userId = $request->get('jwt_user_id');
        $usuario = DB::table('usuarios')->where('id', $userId)->first();

        return response()->json([
            'usuario' => $usuario
        ]);
    }

    public function refresh(Request $request)
    {
        try {
            $authHeader = $request->header('Authorization');

            if(!$authHeader) {
                return response()->json(['error' => 'Token de refresco requerido'], 401);
            }

            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));

            if($decoded->type !== 'refresh') {
                return response()->json(['error' => 'Token de refresco inválido'], 401);
            }

            $newAccessToken = $this->generateToken($decoded->sub, $decoded->rol);

            return response()->json([
                'access_token' => $newAccessToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token inválido o expirado',
                'detalle' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        // En un sistema sin estado, el logout se maneja en el cliente
        return response()->json(['message' => 'Logout exitoso']);
    }
}
