<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
            ['nombre' => 'Técnico', 'descripcion' => 'Acceso a módulos técnicos'],
            ['nombre' => 'Supervisor', 'descripcion' => 'Valida y revisa encuestas'],
            ['nombre' => 'Consulta Pública', 'descripcion' => 'Solo lectura'],
        ];

        DB::table('roles')->insert($roles);

        $permisos = [
            ['nombre' => 'ver_usuarios', 'descripcion' => 'Puede ver la lista de usuarios'],
            ['nombre' => 'crear_usuarios', 'descripcion' => 'Puede crear nuevos usuarios'],
            ['nombre' => 'editar_usuarios', 'descripcion' => 'Puede editar usuarios'],
            ['nombre' => 'eliminar_usuarios', 'descripcion' => 'Puede eliminar usuarios'],
        ];

        DB::table('permisos')->insert($permisos);

        //Relacionar permisos con roles
        $admin = DB::table('roles')->where('nombre', 'Administrador')->first();
        $permisosIds = DB::table('permisos')->pluck('id');

        foreach ($permisosIds as $permisoId) {
            DB::table('rol_permisos')->insert([
                'rol_id' => $admin->id,
                'permiso_id' => $permisoId,
            ]);
        }

        //Usuario administrador por defecto
        DB::table('usuarios')->insert([
            'email' => 'admin@sistema.com',
            'dni' => '12345678',
            'nombre' => 'Admin',
            'apellido' => 'Sistema',
            'direccion' => 'Calle Falsa 123',
            'celular' => '999999999',
            'password' => Hash::make('admin123'),
            'rol_id' => $admin->id,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
