<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgriAsignacionesInsumosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de encuestadores y supervisores
        $encuestadores = DB::table('agri_encuestadores_insumos')
            ->orderBy('id')
            ->pluck('id', 'apellido_paterno')
            ->toArray();

        $supervisores = DB::table('agri_supervisores_insumos')
            ->orderBy('id')
            ->pluck('id', 'apellido_paterno')
            ->toArray();

        // Roberto Mendoza (Jefe Regional) supervisa a todos
        $jefeRegional = $supervisores['Mendoza'] ?? null;

        // Patricia Vega (Senior Provincial - Arequipa)
        $seniorArequipa = $supervisores['Vega'] ?? null;

        // Miguel Castillo (Junior Provincial - Camaná)
        $juniorCamana = $supervisores['Castillo'] ?? null;

        // Carmen Paredes (Senior Provincial - Caylloma)
        $seniorCaylloma = $supervisores['Paredes'] ?? null;

        $asignaciones = [
            // Juan García (Cercado) → Patricia Vega (Prov. Arequipa)
            [
                'encuestador_id' => $encuestadores['García'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Arequipa',
                'distrito' => 'Arequipa',
                'tipo_formulario' => 'F-1',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Cercado', 'nota' => 'Encargado de precios de maquinaria']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['García'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Arequipa',
                'distrito' => 'Arequipa',
                'tipo_formulario' => 'F-4',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Cercado', 'nota' => 'Encargado de precios de fertilizantes']),
                'asignado_por' => 1,
            ],

            // María Rodríguez (Camaná) → Miguel Castillo (Prov. Camaná)
            [
                'encuestador_id' => $encuestadores['Rodríguez'] ?? null,
                'supervisor_id' => $juniorCamana,
                'region' => 'Arequipa',
                'provincia' => 'Camaná',
                'distrito' => 'Camaná',
                'tipo_formulario' => 'F-6',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Camaná centro', 'nota' => 'Encargada de precios de agroquímicos']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Rodríguez'] ?? null,
                'supervisor_id' => $juniorCamana,
                'region' => 'Arequipa',
                'provincia' => 'Camaná',
                'distrito' => 'Camaná',
                'tipo_formulario' => 'F-4',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Camaná centro', 'nota' => 'Encargada de precios de fertilizantes']),
                'asignado_por' => 1,
            ],

            // Pedro Mamani (Caylloma-Chivay) → Carmen Paredes (Prov. Caylloma)
            [
                'encuestador_id' => $encuestadores['Mamani'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'Caylloma',
                'distrito' => 'Chivay',
                'tipo_formulario' => 'F-1',
                'fecha_inicio' => '2024-02-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Zona alto andina', 'nota' => 'Encargado de precios de maquinaria']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Mamani'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'Caylloma',
                'distrito' => 'Chivay',
                'tipo_formulario' => 'F-14',
                'fecha_inicio' => '2024-02-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Zona alto andina', 'nota' => 'Encargado de precios de transporte']),
                'asignado_por' => 1,
            ],

            // Ana Flores (Islay-Mollendo) → Patricia Vega (Prov. Arequipa, supervisa también Islay)
            [
                'encuestador_id' => $encuestadores['Flores'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Islay',
                'distrito' => 'Mollendo',
                'tipo_formulario' => 'F-4',
                'fecha_inicio' => '2024-02-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Zona costera', 'nota' => 'Encargada de precios de fertilizantes']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Flores'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Islay',
                'distrito' => 'Mollendo',
                'tipo_formulario' => 'F-6',
                'fecha_inicio' => '2024-02-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Zona costera', 'nota' => 'Encargada de precios de agroquímicos']),
                'asignado_por' => 1,
            ],

            // Luis Puma (Condesuyos-Chuquibamba) → Carmen Paredes (Prov. Caylloma, supervisa también Condesuyos)
            [
                'encuestador_id' => $encuestadores['Puma'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'Condesuyos',
                'distrito' => 'Chuquibamba',
                'tipo_formulario' => 'F-14',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Condesuyos', 'nota' => 'Encargado de precios de transporte']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Puma'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'Condesuyos',
                'distrito' => 'Chuquibamba',
                'tipo_formulario' => 'F-1',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Condesuyos', 'nota' => 'Encargado de precios de maquinaria']),
                'asignado_por' => 1,
            ],

            // Rosa Huamán (La Unión-Cotahuasi) → Carmen Paredes (supervisa también La Unión)
            [
                'encuestador_id' => $encuestadores['Huamán'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'La Unión',
                'distrito' => 'Cotahuasi',
                'tipo_formulario' => 'F-6',
                'fecha_inicio' => '2024-03-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Cotahuasi', 'nota' => 'Encargada de precios de agroquímicos']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Huamán'] ?? null,
                'supervisor_id' => $seniorCaylloma,
                'region' => 'Arequipa',
                'provincia' => 'La Unión',
                'distrito' => 'Cotahuasi',
                'tipo_formulario' => 'F-14',
                'fecha_inicio' => '2024-03-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Cotahuasi', 'nota' => 'Encargada de precios de transporte']),
                'asignado_por' => 1,
            ],

            // Carlos Salas (Castilla-Aplao) → Miguel Castillo (supervisa también Castilla)
            [
                'encuestador_id' => $encuestadores['Salas'] ?? null,
                'supervisor_id' => $juniorCamana,
                'region' => 'Arequipa',
                'provincia' => 'Castilla',
                'distrito' => 'Aplao',
                'tipo_formulario' => 'F-1',
                'fecha_inicio' => '2024-04-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Valle de Majes', 'nota' => 'Encargado de precios de maquinaria']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Salas'] ?? null,
                'supervisor_id' => $juniorCamana,
                'region' => 'Arequipa',
                'provincia' => 'Castilla',
                'distrito' => 'Aplao',
                'tipo_formulario' => 'F-4',
                'fecha_inicio' => '2024-04-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Valle de Majes', 'nota' => 'Encargado de precios de fertilizantes']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Salas'] ?? null,
                'supervisor_id' => $juniorCamana,
                'region' => 'Arequipa',
                'provincia' => 'Castilla',
                'distrito' => 'Aplao',
                'tipo_formulario' => 'F-6',
                'fecha_inicio' => '2024-04-01',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Valle de Majes', 'nota' => 'Encargado de precios de agroquímicos']),
                'asignado_por' => 1,
            ],

            // Sofía Cárdenas (Yanahuara) → Patricia Vega (Prov. Arequipa)
            [
                'encuestador_id' => $encuestadores['Cárdenas'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Arequipa',
                'distrito' => 'Yanahuara',
                'tipo_formulario' => 'F-4',
                'fecha_inicio' => '2024-04-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Yanahuara', 'nota' => 'Encargada de precios de fertilizantes']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Cárdenas'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Arequipa',
                'distrito' => 'Yanahuara',
                'tipo_formulario' => 'F-6',
                'fecha_inicio' => '2024-04-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Yanahuara', 'nota' => 'Encargada de precios de agroquímicos']),
                'asignado_por' => 1,
            ],
            [
                'encuestador_id' => $encuestadores['Cárdenas'] ?? null,
                'supervisor_id' => $seniorArequipa,
                'region' => 'Arequipa',
                'provincia' => 'Arequipa',
                'distrito' => 'Yanahuara',
                'tipo_formulario' => 'F-14',
                'fecha_inicio' => '2024-04-15',
                'fecha_fin' => null,
                'estado' => 'activa',
                'restricciones' => json_encode(['area' => 'Yanahuara', 'nota' => 'Encargada de precios de transporte']),
                'asignado_por' => 1,
            ],
        ];

        $insertados = 0;
        foreach ($asignaciones as $asignacion) {
            // Solo insertar si tenemos encuestador y supervisor
            if ($asignacion['encuestador_id'] && $asignacion['supervisor_id']) {
                DB::table('agri_asignaciones_insumos')->insert(array_merge($asignacion, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                $insertados++;
            }
        }

        $this->command->info('✅ Se insertaron ' . $insertados . ' asignaciones');
    }
}
