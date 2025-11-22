<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
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

        $credentials = $request->only('email', 'password');

        // Usar el modelo Usuario con la relación rol
        $usuario = Usuario::with(['rol.permisos'])
            ->where('email', $credentials['email'])
            ->first();

        if (!$usuario || !Hash::check($credentials['password'], $usuario->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Verificar si el usuario está activo
        if (!$usuario->activo) {
            return response()->json(['error' => 'Usuario desactivado'], 403);
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
                'rol' => [
                    'id' => $usuario->rol->id,
                    'nombre' => $usuario->rol->nombre,
                    'permisos' => $usuario->rol->permisos->map(function ($permiso) {
                        return [
                            'id' => $permiso->id,
                            'nombre' => $permiso->nombre,
                            'descripcion' => $permiso->descripcion,
                        ];
                    }),
                ],
            ],
        ]);
    }

    public function perfil(Request $request)
    {
        $userId = $request->get('jwt_user_id');

        // Usar el modelo Usuario con la relación rol y permisos
        $usuario = Usuario::with(['rol.permisos'])
            ->where('id', $userId)
            ->first();

        if (!$usuario) {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'usuario' => [
                'id' => $usuario->id,
                'email' => $usuario->email,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'dni' => $usuario->dni,
                'direccion' => $usuario->direccion,
                'celular' => $usuario->celular,
                'rol_id' => $usuario->rol_id,
                'activo' => $usuario->activo,
                'rol' => [
                    'id' => $usuario->rol->id,
                    'nombre' => $usuario->rol->nombre,
                    'permisos' => $usuario->rol->permisos->map(function ($permiso) {
                        return [
                            'id' => $permiso->id,
                            'nombre' => $permiso->nombre,
                            'descripcion' => $permiso->descripcion,
                        ];
                    }),
                ],
            ],
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

    public function actualizarPerfil(Request $request)
    {
        $userId = $request->get('jwt_user_id');

        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        // Validación de campos
        $mensajes = [
            'email.email' => 'El email debe ser válido.',
            'email.unique' => 'Este email ya está en uso.',
            'dni.unique' => 'Este DNI ya está en uso.',
            'dni.max' => 'El DNI no puede tener más de 20 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'apellido.max' => 'El apellido no puede tener más de 100 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 150 caracteres.',
            'celular.max' => 'El celular no puede tener más de 20 caracteres.',
        ];

        $validated = $request->validate([
            'email' => ['sometimes', 'email', "unique:usuarios,email,{$usuario->id}"],
            'dni' => ['sometimes', 'string', 'max:20', "unique:usuarios,dni,{$usuario->id}"],
            'nombre' => ['sometimes', 'string', 'max:100'],
            'apellido' => ['sometimes', 'string', 'max:100'],
            'direccion' => ['sometimes', 'nullable', 'string', 'max:150'],
            'celular' => ['sometimes', 'nullable', 'string', 'max:20'],
        ], $mensajes);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No se enviaron datos para actualizar.'
            ], 422);
        }

        // Actualizar solo los campos enviados
        $usuario->fill($validated);

        if (!$usuario->isDirty()) {
            return response()->json([
                'message' => 'No hay cambios para aplicar.'
            ], 422);
        }

        $usuario->save();

        // Recargar el usuario con sus relaciones
        $usuario->load(['rol.permisos']);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'usuario' => [
                'id' => $usuario->id,
                'email' => $usuario->email,
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'dni' => $usuario->dni,
                'direccion' => $usuario->direccion,
                'celular' => $usuario->celular,
                'rol_id' => $usuario->rol_id,
                'activo' => $usuario->activo,
                'rol' => [
                    'id' => $usuario->rol->id,
                    'nombre' => $usuario->rol->nombre,
                    'permisos' => $usuario->rol->permisos->map(function ($permiso) {
                        return [
                            'id' => $permiso->id,
                            'nombre' => $permiso->nombre,
                            'descripcion' => $permiso->descripcion,
                        ];
                    }),
                ],
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        // En un sistema sin estado, el logout se maneja en el cliente
        return response()->json(['message' => 'Logout exitoso']);
    }
}
