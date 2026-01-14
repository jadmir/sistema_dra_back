<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriSupervisoresInsumosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supervisores = [
            [
                'dni' => '40123456',
                'nombres' => 'Roberto Carlos',
                'apellido_paterno' => 'Mendoza',
                'apellido_materno' => 'Silva',
                'fecha_nacimiento' => '1980-03-15',
                'genero' => 'M',
                'telefono' => '987111222',
                'email' => 'roberto.mendoza@dra.gob.pe',
                'direccion' => 'Av. Arequipa 1000, Arequipa',
                'region_asignada' => 'Arequipa',
                'provincia_asignada' => null,
                'ambito_supervision' => 'regional',
                'cargo' => 'Supervisor SIEA',
                'nivel' => 'jefe',
                'especializacion' => 'maquinaria,fertilizantes,agroquimicos,transporte',
                'usuario_id' => null,
                'firma_path' => null,
                'firma_activa' => true,
                'fecha_ingreso' => '2020-01-15',
                'fecha_salida' => null,
                'estado' => 'activo',
                'motivo_inactividad' => null,
                'fecha_capacitacion' => '2020-02-01',
                'certificaciones' => json_encode(['SIEA Master', 'Gestión de Calidad', 'Auditoría Estadística']),
                'created_by' => 1,
            ],
            [
                'dni' => '40234567',
                'nombres' => 'Patricia Alexandra',
                'apellido_paterno' => 'Vega',
                'apellido_materno' => 'Morales',
                'fecha_nacimiento' => '1985-06-22',
                'genero' => 'F',
                'telefono' => '987222333',
                'email' => 'patricia.vega@dra.gob.pe',
                'direccion' => 'Calle San José 234, Arequipa',
                'region_asignada' => 'Arequipa',
                'provincia_asignada' => 'Arequipa',
                'ambito_supervision' => 'provincial',
                'cargo' => 'Supervisor SIEA',
                'nivel' => 'senior',
                'especializacion' => 'maquinaria,fertilizantes',
                'usuario_id' => null,
                'firma_path' => null,
                'firma_activa' => true,
                'fecha_ingreso' => '2021-03-01',
                'fecha_salida' => null,
                'estado' => 'activo',
                'motivo_inactividad' => null,
                'fecha_capacitacion' => '2021-03-15',
                'certificaciones' => json_encode(['SIEA Avanzado', 'Supervisión de Campo']),
                'created_by' => 1,
            ],
            [
                'dni' => '40345678',
                'nombres' => 'Miguel Ángel',
                'apellido_paterno' => 'Castillo',
                'apellido_materno' => 'Ramos',
                'fecha_nacimiento' => '1988-09-10',
                'genero' => 'M',
                'telefono' => '987333444',
                'email' => 'miguel.castillo@dra.gob.pe',
                'direccion' => 'Jr. Lima 567, Arequipa',
                'region_asignada' => 'Arequipa',
                'provincia_asignada' => 'Camaná',
                'ambito_supervision' => 'provincial',
                'cargo' => 'Supervisor SIEA',
                'nivel' => 'junior',
                'especializacion' => 'agroquimicos,transporte',
                'usuario_id' => null,
                'firma_path' => null,
                'firma_activa' => true,
                'fecha_ingreso' => '2023-01-10',
                'fecha_salida' => null,
                'estado' => 'activo',
                'motivo_inactividad' => null,
                'fecha_capacitacion' => '2023-02-01',
                'certificaciones' => json_encode(['SIEA Básico']),
                'created_by' => 1,
            ],
            [
                'dni' => '40456789',
                'nombres' => 'Carmen Rosa',
                'apellido_paterno' => 'Paredes',
                'apellido_materno' => 'Gutiérrez',
                'fecha_nacimiento' => '1986-12-18',
                'genero' => 'F',
                'telefono' => '987444555',
                'email' => 'carmen.paredes@dra.gob.pe',
                'direccion' => 'Av. Goyeneche 890, Arequipa',
                'region_asignada' => 'Arequipa',
                'provincia_asignada' => 'Caylloma',
                'ambito_supervision' => 'provincial',
                'cargo' => 'Supervisor SIEA',
                'nivel' => 'senior',
                'especializacion' => 'maquinaria,fertilizantes,agroquimicos',
                'usuario_id' => null,
                'firma_path' => null,
                'firma_activa' => true,
                'fecha_ingreso' => '2022-05-15',
                'fecha_salida' => null,
                'estado' => 'activo',
                'motivo_inactividad' => null,
                'fecha_capacitacion' => '2022-06-01',
                'certificaciones' => json_encode(['SIEA Avanzado', 'Control de Calidad']),
                'created_by' => 1,
            ],
        ];

        foreach ($supervisores as $supervisor) {
            DB::table('agri_supervisores_insumos')->insert(array_merge($supervisor, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Se insertaron ' . count($supervisores) . ' supervisores');
    }
}
