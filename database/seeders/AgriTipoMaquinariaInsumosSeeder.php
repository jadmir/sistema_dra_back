<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriTipoMaquinariaInsumosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maquinarias = [
            // TRACTORES
            [
                'nombre' => 'Tractor 40 H.P.',
                'categoria' => 'tractor',
                'codigo' => 'TRA-40',
                'descripcion' => 'Tractor agrícola de 40 caballos de fuerza',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['potencia' => '40 HP', 'tipo' => 'agrícola']),
                'activo' => true,
            ],
            [
                'nombre' => 'Tractor 42 H.P.',
                'categoria' => 'tractor',
                'codigo' => 'TRA-42',
                'descripcion' => 'Tractor agrícola de 42 caballos de fuerza',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['potencia' => '42 HP', 'tipo' => 'agrícola']),
                'activo' => true,
            ],
            [
                'nombre' => 'Tractor 60 H.P.',
                'categoria' => 'tractor',
                'codigo' => 'TRA-60',
                'descripcion' => 'Tractor agrícola de 60 caballos de fuerza',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['potencia' => '60 HP', 'tipo' => 'agrícola']),
                'activo' => true,
            ],
            [
                'nombre' => 'Tractor 80 H.P.',
                'categoria' => 'tractor',
                'codigo' => 'TRA-80',
                'descripcion' => 'Tractor agrícola de 80 caballos de fuerza',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['potencia' => '80 HP', 'tipo' => 'agrícola']),
                'activo' => true,
            ],

            // OTRA MAQUINARIA
            [
                'nombre' => 'Surcadora',
                'categoria' => 'maquinaria',
                'codigo' => 'MAQ-SUR',
                'descripcion' => 'Maquinaria para hacer surcos en el terreno',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['tipo' => 'implemento agrícola']),
                'activo' => true,
            ],
            [
                'nombre' => 'Cosechadora',
                'categoria' => 'maquinaria',
                'codigo' => 'MAQ-COS',
                'descripcion' => 'Maquinaria para cosecha de cultivos',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['tipo' => 'cosecha']),
                'activo' => true,
            ],
            [
                'nombre' => 'Trilladora',
                'categoria' => 'maquinaria',
                'codigo' => 'MAQ-TRI',
                'descripcion' => 'Maquinaria para trillar granos',
                'unidad_medida' => 'hora',
                'especificaciones' => json_encode(['tipo' => 'procesamiento']),
                'activo' => true,
            ],

            // MANO DE OBRA
            [
                'nombre' => 'Mano de obra - Hombres',
                'categoria' => 'mano_obra',
                'codigo' => 'MO-HOM',
                'descripcion' => 'Jornada de trabajo - Hombres',
                'unidad_medida' => 'jornal',
                'especificaciones' => json_encode(['genero' => 'masculino']),
                'activo' => true,
            ],
            [
                'nombre' => 'Mano de obra - Mujeres',
                'categoria' => 'mano_obra',
                'codigo' => 'MO-MUJ',
                'descripcion' => 'Jornada de trabajo - Mujeres',
                'unidad_medida' => 'jornal',
                'especificaciones' => json_encode(['genero' => 'femenino']),
                'activo' => true,
            ],

            // YUNTA
            [
                'nombre' => 'Yunta',
                'categoria' => 'yunta',
                'codigo' => 'YUN-01',
                'descripcion' => 'Pareja de bueyes para arar',
                'unidad_medida' => 'día',
                'especificaciones' => json_encode(['tipo' => 'tradicional']),
                'activo' => true,
            ],
        ];

        foreach ($maquinarias as $maquinaria) {
            DB::table('agri_tipo_maquinaria_insumos')->insert(array_merge($maquinaria, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Se insertaron ' . count($maquinarias) . ' tipos de maquinaria');
    }
}
