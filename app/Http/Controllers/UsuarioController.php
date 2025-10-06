<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Listar usuarios.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $usuarios = Usuario::with('rol')
                ->where('activo', 1) // solo activos
                ->paginate($perPage);

            return response()->json($usuarios, 200);
        } catch (\Throwable $e) {
            // Captura cualquier excepción y responde con un JSON amigable
            return response()->json([
                'message' => 'Error al obtener la lista de usuarios.',
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        $mensajes = [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El correo electrónico no es válido.',
            'email.unique'      => 'El correo electrónico ya está registrado.',
            'dni.required'      => 'El DNI es obligatorio.',
            'dni.digits'        => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'        => 'El DNI ya está registrado.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'rol_id.required'   => 'El rol es obligatorio.',
            'rol_id.exists'     => 'El rol seleccionado no existe.',
            'activo.boolean'    => 'El campo activo debe ser booleano.'
        ];

        $validated = $request->validate([
            'email'    => 'required|email|unique:usuarios,email',
            'dni'      => 'required|digits:8|unique:usuarios,dni',
            'nombre'   => 'required|string',
            'apellido' => 'required|string',
            'direccion'=> 'nullable|string',
            'celular'  => 'nullable|digits_between:9,15',
            'password' => 'required|string|min:6',
            'rol_id'   => 'required|exists:roles,id',
            'activo'   => 'sometimes|boolean'
        ], $mensajes);

        try {
            // casteos y hashing
            $validated['dni'] = (string)$validated['dni'];
            if (isset($validated['celular'])) {
                $validated['celular'] = (string)$validated['celular'];
            }
            $validated['password'] = Hash::make($validated['password']);
            $validated['activo'] = $validated['activo'] ?? 1;

            $usuario = Usuario::create($validated)->load('rol');

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'usuario' => $usuario,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el usuario.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with('rol')->find($id);
            if (!$usuario || (int)$usuario->activo === 0) {
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }
            return response()->json($usuario, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener el usuario.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        // Buscar el usuario manualmente
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        if ((int)$usuario->activo === 0) {
            return response()->json(['message' => 'Usuario eliminado o inactivo.'], 409);
        }
        if (!$request->all()) {
            return response()->json(['message' => 'No se recibieron campos para actualizar.'], 422);
        }

        $validated = $request->validate([
            'email'     => ['sometimes','filled','email', Rule::unique('usuarios','email')->ignore($usuario->id)],
            'dni'       => ['sometimes','filled','digits:8', Rule::unique('usuarios','dni')->ignore($usuario->id)],
            'nombre'    => ['sometimes','filled','string'],
            'apellido'  => ['sometimes','filled','string'],
            'direccion' => ['sometimes','nullable','string'],
            'celular'   => ['sometimes','nullable','digits_between:9,15'],
            'rol_id'    => ['sometimes','filled','exists:roles,id'],
            'activo'    => ['sometimes','boolean'],
        ], [
            'email.email'   => 'El correo electrónico no es válido.',
            'email.unique'  => 'El correo electrónico ya está registrado.',
            'dni.digits'    => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique'    => 'El DNI ya está registrado.',
            'rol_id.exists' => 'El rol seleccionado no existe.',
            'celular.digits_between' => 'El celular debe tener entre 9 y 15 dígitos.',
            'activo.boolean' => 'El campo activo debe ser booleano.'
        ]);

        try {
            // 3) Normaliza tipos (conserva ceros a la izquierda)
            if (array_key_exists('dni', $validated)) {
                $validated['dni'] = (string)$validated['dni'];
            }
            if (array_key_exists('celular', $validated) && $validated['celular'] !== null) {
                $validated['celular'] = (string)$validated['celular'];
            }

            $usuario->update($validated);
            $usuario->refresh()->load('rol');

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'usuario' => $usuario,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el usuario.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar manualmente para poder controlar el 404 aquí
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        if ((int)$usuario->activo === 0) {
            return response()->json(['message' => 'El usuario ya estaba inactivo'], 200);
        }
        $usuario->activo = 0;
        $usuario->save();

        return response()->json(['message' => 'Usuario desactivado correctamente'], 200);
    }

    /**
     * cambiar contraseña de usuario propio
     */
    public function cambiarPassword(Request $request)
    {
        try {
            $request->validate([
                'password_actual'            => ['required','string'],
                'nueva_password'             => ['required','string','min:6','confirmed'], // requiere nueva_password_confirmation
            ], [
                'password_actual.required'   => 'La contraseña actual es obligatoria.',
                'nueva_password.required'    => 'La nueva contraseña es obligatoria.',
                'nueva_password.min'         => 'La nueva contraseña debe tener al menos 6 caracteres.',
                'nueva_password.confirmed'   => 'La confirmación de la nueva contraseña no coincide.',
            ]);

            $usuario = $request->user();

            if ((int)$usuario->activo === 0) {
                return response()->json(['message' => 'Usuario inactivo.'], 409);
            }

            if (!Hash::check($request->input('password_actual'), $usuario->password)) {
                return response()->json(['message' => 'La contraseña actual es incorrecta.'], 422);
            }

            // Guardar nueva
            $usuario->password = Hash::make($request->nueva_password);
            $usuario->save();

            return response()->json([
                'message' => 'Tu contraseña ha sido actualizada. Inicia sesión nuevamente.'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar la contraseña.'
            ], 500);
        }


    }

    //buscqueda de usuarios
    public function search(Request $request)
    {
        try {
            $request->validate([
                'q'         => ['nullable','string'],
                'email'     => ['nullable','string'],
                'dni'       => ['nullable'],
                'nombre'    => ['nullable','string'],
                'apellido'  => ['nullable','string'],
                'direccion' => ['nullable','string'],
                'celular'   => ['nullable'],
                'rol_id'    => ['nullable','integer'],
                'activo'    => ['nullable','in:0,1'],
                'per_page'  => ['nullable','integer','min:1','max:100'],
                'sort_by'   => ['nullable','string'],
                'sort_dir'  => ['nullable','in:asc,desc'],
            ]);

            $perPage = (int)$request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $query = Usuario::with('rol');

            // Lógica de activos
            if ($request->has('activo')) {
                // Incluir todos; el filtro exacto lo aplicará scopeFilter
                $query->activos(false);
            } else {
                // Solo activos por defecto
                $query->activos();
            }

            $query->search($request->input('q'))
                  ->filter($request->only([
                      'email','dni','nombre','apellido','direccion','celular','rol_id','activo'
                  ]))
                  ->sortBy($request->input('sort_by'), $request->input('sort_dir'));

            $usuarios = $query->paginate($perPage);

            return response()->json($usuarios, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.',
            ], 500);
        }
    }


}
