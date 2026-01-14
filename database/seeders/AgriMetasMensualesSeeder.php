<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgriMetasMensualesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de encuestadores
        $encuestadores = DB::table('agri_encuestadores_insumos')
            ->orderBy('id')
            ->pluck('id', 'apellido_paterno')
            ->toArray();

        // Metas para Noviembre 2025 (mes actual)
        $año = 2025;
        $mes = 11;
        $fecha_inicio = Carbon::create($año, $mes, 1)->toDateString();
        $fecha_fin = Carbon::create($año, $mes, 1)->endOfMonth()->toDateString();

        $metas = [
            // Juan Carlos García (Arequipa - Cercado)
            // Especialización: maquinaria, fertilizantes
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['García'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Arequipa',
                'meta_f1' => 20,  // Maquinaria
                'meta_f4' => 25,  // Fertilizantes
                'meta_f6' => 0,   // Agroquímicos (no es su especialidad)
                'meta_f14' => 0,  // Transporte (no es su especialidad)
                'meta_total' => 45,
                'logrado_f1' => 22,
                'logrado_f4' => 27,
                'logrado_f6' => 0,
                'logrado_f14' => 0,
                'logrado_total' => 49,
                'porcentaje_f1' => 110.00,
                'porcentaje_f4' => 108.00,
                'porcentaje_f6' => 0.00,
                'porcentaje_f14' => 0.00,
                'porcentaje_total' => 108.89,
                'estado' => 'superado',
                'observaciones' => 'Excelente desempeño. Superó la meta en un 8.89%',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // María Elena Rodríguez (Camaná)
            // Especialización: fertilizantes, agroquímicos
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Rodríguez'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Camaná',
                'meta_f1' => 0,
                'meta_f4' => 20,
                'meta_f6' => 20,
                'meta_f14' => 0,
                'meta_total' => 40,
                'logrado_f1' => 0,
                'logrado_f4' => 18,
                'logrado_f6' => 19,
                'logrado_f14' => 0,
                'logrado_total' => 37,
                'porcentaje_f1' => 0.00,
                'porcentaje_f4' => 90.00,
                'porcentaje_f6' => 95.00,
                'porcentaje_f14' => 0.00,
                'porcentaje_total' => 92.50,
                'estado' => 'en_progreso',
                'observaciones' => 'Buen avance. Le faltan 3 encuestas para cumplir meta',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Pedro Antonio Mamani (Caylloma - Chivay)
            // Especialización: maquinaria, transporte
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Mamani'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Caylloma',
                'meta_f1' => 15,
                'meta_f4' => 0,
                'meta_f6' => 0,
                'meta_f14' => 15,
                'meta_total' => 30,
                'logrado_f1' => 16,
                'logrado_f4' => 0,
                'logrado_f6' => 0,
                'logrado_f14' => 15,
                'logrado_total' => 31,
                'porcentaje_f1' => 106.67,
                'porcentaje_f4' => 0.00,
                'porcentaje_f6' => 0.00,
                'porcentaje_f14' => 100.00,
                'porcentaje_total' => 103.33,
                'estado' => 'cumplido',
                'observaciones' => 'Meta cumplida exitosamente en zona rural difícil',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Ana Lucía Flores (Islay - Mollendo)
            // Especialización: fertilizantes, agroquímicos
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Flores'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Islay',
                'meta_f1' => 0,
                'meta_f4' => 22,
                'meta_f6' => 18,
                'meta_f14' => 0,
                'meta_total' => 40,
                'logrado_f1' => 0,
                'logrado_f4' => 24,
                'logrado_f6' => 21,
                'logrado_f14' => 0,
                'logrado_total' => 45,
                'porcentaje_f1' => 0.00,
                'porcentaje_f4' => 109.09,
                'porcentaje_f6' => 116.67,
                'porcentaje_f14' => 0.00,
                'porcentaje_total' => 112.50,
                'estado' => 'superado',
                'observaciones' => 'Superó ampliamente la meta. Zona costera con buena cobertura',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Luis Fernando Puma (Condesuyos - Chuquibamba)
            // Especialización: maquinaria, transporte
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Puma'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Condesuyos',
                'meta_f1' => 12,
                'meta_f4' => 0,
                'meta_f6' => 0,
                'meta_f14' => 13,
                'meta_total' => 25,
                'logrado_f1' => 10,
                'logrado_f4' => 0,
                'logrado_f6' => 0,
                'logrado_f14' => 11,
                'logrado_total' => 21,
                'porcentaje_f1' => 83.33,
                'porcentaje_f4' => 0.00,
                'porcentaje_f6' => 0.00,
                'porcentaje_f14' => 84.62,
                'porcentaje_total' => 84.00,
                'estado' => 'en_progreso',
                'observaciones' => 'Zona de difícil acceso. Requiere apoyo adicional',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Rosa María Huamán (La Unión - Cotahuasi)
            // Especialización: agroquímicos, transporte
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Huamán'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'La Unión',
                'meta_f1' => 0,
                'meta_f4' => 0,
                'meta_f6' => 15,
                'meta_f14' => 15,
                'meta_total' => 30,
                'logrado_f1' => 0,
                'logrado_f4' => 0,
                'logrado_f6' => 15,
                'logrado_f14' => 14,
                'logrado_total' => 29,
                'porcentaje_f1' => 0.00,
                'porcentaje_f4' => 0.00,
                'porcentaje_f6' => 100.00,
                'porcentaje_f14' => 93.33,
                'porcentaje_total' => 96.67,
                'estado' => 'en_progreso',
                'observaciones' => 'Muy cerca de cumplir la meta. Solo falta 1 encuesta',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Carlos Alberto Salas (Castilla - Aplao)
            // Especialización: maquinaria, fertilizantes, agroquímicos
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Salas'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Castilla',
                'meta_f1' => 18,
                'meta_f4' => 20,
                'meta_f6' => 17,
                'meta_f14' => 0,
                'meta_total' => 55,
                'logrado_f1' => 20,
                'logrado_f4' => 23,
                'logrado_f6' => 19,
                'logrado_f14' => 0,
                'logrado_total' => 62,
                'porcentaje_f1' => 111.11,
                'porcentaje_f4' => 115.00,
                'porcentaje_f6' => 111.76,
                'porcentaje_f14' => 0.00,
                'porcentaje_total' => 112.73,
                'estado' => 'superado',
                'observaciones' => 'Encuestador más productivo del mes. Excelente trabajo',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // Sofía Isabel Cárdenas (Arequipa - Yanahuara)
            // Especialización: fertilizantes, agroquímicos, transporte
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'encuestador',
                'encuestador_id' => $encuestadores['Cárdenas'] ?? null,
                'supervisor_id' => null,
                'provincia' => 'Arequipa',
                'meta_f1' => 0,
                'meta_f4' => 20,
                'meta_f6' => 18,
                'meta_f14' => 12,
                'meta_total' => 50,
                'logrado_f1' => 0,
                'logrado_f4' => 21,
                'logrado_f6' => 19,
                'logrado_f14' => 13,
                'logrado_total' => 53,
                'porcentaje_f1' => 0.00,
                'porcentaje_f4' => 105.00,
                'porcentaje_f6' => 105.56,
                'porcentaje_f14' => 108.33,
                'porcentaje_total' => 106.00,
                'estado' => 'superado',
                'observaciones' => 'Superó la meta en todas las especializaciones',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],

            // META REGIONAL (Toda la región Arequipa)
            [
                'año' => $año,
                'mes' => $mes,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplica_a' => 'regional',
                'encuestador_id' => null,
                'supervisor_id' => null,
                'provincia' => null,
                'meta_f1' => 85,
                'meta_f4' => 107,
                'meta_f6' => 88,
                'meta_f14' => 55,
                'meta_total' => 335,
                'logrado_f1' => 88,
                'logrado_f4' => 113,
                'logrado_f6' => 93,
                'logrado_f14' => 53,
                'logrado_total' => 347,
                'porcentaje_f1' => 103.53,
                'porcentaje_f4' => 105.61,
                'porcentaje_f6' => 105.68,
                'porcentaje_f14' => 96.36,
                'porcentaje_total' => 103.58,
                'estado' => 'cumplido',
                'observaciones' => 'Meta regional cumplida. Transporte ligeramente bajo',
                'establecido_por' => 1,
                'ultima_actualizacion_logros' => now(),
            ],
        ];

        $insertados = 0;
        foreach ($metas as $meta) {
            // Solo insertar si tiene encuestador válido o es meta regional/provincial
            if ($meta['encuestador_id'] || in_array($meta['aplica_a'], ['regional', 'provincial'])) {
                DB::table('agri_metas_mensuales')->insert(array_merge($meta, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                $insertados++;
            }
        }

        $this->command->info('✅ Se insertaron ' . $insertados . ' metas mensuales (Noviembre 2025)');
    }
}
