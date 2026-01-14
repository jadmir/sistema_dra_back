<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgriAnalisisPreciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $analisis = [];

        // Obtener productos de los catálogos
        $maquinarias = DB::table('agri_tipo_maquinaria_insumos')->get();
        $fertilizantes = DB::table('agri_fertilizantes_insumos')->get();
        $agroquimicos = DB::table('agri_agroquimicos_insumos')->get();

        $fecha_actual = Carbon::now()->startOfMonth();
        $fecha_anterior = Carbon::now()->subMonth()->startOfMonth();

        // ============================================
        // F-1: ANÁLISIS DE PRECIOS DE MAQUINARIA
        // ============================================

        // Tractor 40 H.P. - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-1',
            'producto_id' => 1,
            'producto_nombre' => 'Tractor 40 H.P.',
            'producto_categoria' => 'Tractor',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 245.50,
            'precio_minimo' => 220.00,
            'precio_maximo' => 270.00,
            'precio_mediana' => 245.00,
            'desviacion_estandar' => 12.50,
            'coeficiente_variacion' => 5.09,
            'num_registros' => 45,
            'num_encuestas' => 15,
            'num_casas_comerciales' => null,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 238.00,
            'variacion_absoluta' => 7.50,
            'variacion_porcentual' => 3.15,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Tractor 80 H.P. - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-1',
            'producto_id' => 4,
            'producto_nombre' => 'Tractor 80 H.P.',
            'producto_categoria' => 'Tractor',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 420.00,
            'precio_minimo' => 380.00,
            'precio_maximo' => 460.00,
            'precio_mediana' => 420.00,
            'desviacion_estandar' => 20.00,
            'coeficiente_variacion' => 4.76,
            'num_registros' => 32,
            'num_encuestas' => 11,
            'num_casas_comerciales' => null,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 405.00,
            'variacion_absoluta' => 15.00,
            'variacion_porcentual' => 3.70,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Cosechadora - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-1',
            'producto_id' => 6,
            'producto_nombre' => 'Cosechadora',
            'producto_categoria' => 'Maquinaria',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Camaná',
            'distrito' => null,
            'precio_promedio' => 580.00,
            'precio_minimo' => 520.00,
            'precio_maximo' => 650.00,
            'precio_mediana' => 575.00,
            'desviacion_estandar' => 35.00,
            'coeficiente_variacion' => 6.03,
            'num_registros' => 18,
            'num_encuestas' => 6,
            'num_casas_comerciales' => null,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 570.00,
            'variacion_absoluta' => 10.00,
            'variacion_porcentual' => 1.75,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // ============================================
        // F-4: ANÁLISIS DE PRECIOS DE FERTILIZANTES
        // ============================================

        // Urea 46% - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-4',
            'producto_id' => 3,
            'producto_nombre' => 'Urea 46%',
            'producto_categoria' => 'Nitrogenados',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 135.50,
            'precio_minimo' => 125.00,
            'precio_maximo' => 145.00,
            'precio_mediana' => 135.00,
            'desviacion_estandar' => 5.20,
            'coeficiente_variacion' => 3.84,
            'num_registros' => 68,
            'num_encuestas' => 23,
            'num_casas_comerciales' => 12,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 138.70,
            'variacion_absoluta' => -3.20,
            'variacion_porcentual' => -2.31,
            'tendencia' => 'disminución',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // DAP - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-4',
            'producto_id' => 5,
            'producto_nombre' => 'DAP (Fosfato Diamónico)',
            'producto_categoria' => 'Fosfatados',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 168.00,
            'precio_minimo' => 158.00,
            'precio_maximo' => 180.00,
            'precio_mediana' => 167.00,
            'desviacion_estandar' => 6.80,
            'coeficiente_variacion' => 4.05,
            'num_registros' => 52,
            'num_encuestas' => 18,
            'num_casas_comerciales' => 10,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 165.50,
            'variacion_absoluta' => 2.50,
            'variacion_porcentual' => 1.51,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Cloruro de Potasio (KCl) - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-4',
            'producto_id' => 9,
            'producto_nombre' => 'Cloruro de Potasio (KCl)',
            'producto_categoria' => 'Potásicos',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Islay',
            'distrito' => null,
            'precio_promedio' => 185.00,
            'precio_minimo' => 175.00,
            'precio_maximo' => 195.00,
            'precio_mediana' => 185.00,
            'desviacion_estandar' => 6.50,
            'coeficiente_variacion' => 3.51,
            'num_registros' => 38,
            'num_encuestas' => 13,
            'num_casas_comerciales' => 8,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 182.00,
            'variacion_absoluta' => 3.00,
            'variacion_porcentual' => 1.65,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // 15-15-15 (Compuesto) - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-4',
            'producto_id' => 13,
            'producto_nombre' => '15-15-15',
            'producto_categoria' => 'Compuestos',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Camaná',
            'distrito' => null,
            'precio_promedio' => 145.00,
            'precio_minimo' => 138.00,
            'precio_maximo' => 152.00,
            'precio_mediana' => 145.00,
            'desviacion_estandar' => 4.20,
            'coeficiente_variacion' => 2.90,
            'num_registros' => 42,
            'num_encuestas' => 14,
            'num_casas_comerciales' => 9,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 143.50,
            'variacion_absoluta' => 1.50,
            'variacion_porcentual' => 1.05,
            'tendencia' => 'estable',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Guano de Isla - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-4',
            'producto_id' => 16,
            'producto_nombre' => 'Guano de Isla',
            'producto_categoria' => 'Orgánicos',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 62.00,
            'precio_minimo' => 58.00,
            'precio_maximo' => 68.00,
            'precio_mediana' => 62.00,
            'desviacion_estandar' => 3.20,
            'coeficiente_variacion' => 5.16,
            'num_registros' => 35,
            'num_encuestas' => 12,
            'num_casas_comerciales' => 7,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 62.50,
            'variacion_absoluta' => -0.50,
            'variacion_porcentual' => -0.80,
            'tendencia' => 'estable',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // ============================================
        // F-6: ANÁLISIS DE PRECIOS DE AGROQUÍMICOS
        // ============================================

        // Karate Zeon (Insecticida) - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-6',
            'producto_id' => 11,
            'producto_nombre' => 'Karate Zeon (Lambda Cyhalothrin)',
            'producto_categoria' => 'Insecticidas',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 145.00,
            'precio_minimo' => 135.00,
            'precio_maximo' => 155.00,
            'precio_mediana' => 145.00,
            'desviacion_estandar' => 5.80,
            'coeficiente_variacion' => 4.00,
            'num_registros' => 32,
            'num_encuestas' => 11,
            'num_casas_comerciales' => 8,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 140.10,
            'variacion_absoluta' => 4.90,
            'variacion_porcentual' => 3.50,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Glifosato (Herbicida) - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-6',
            'producto_id' => 7,
            'producto_nombre' => 'Glifosato 48% SL',
            'producto_categoria' => 'Herbicidas',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Camaná',
            'distrito' => null,
            'precio_promedio' => 28.50,
            'precio_minimo' => 25.00,
            'precio_maximo' => 32.00,
            'precio_mediana' => 28.00,
            'desviacion_estandar' => 2.10,
            'coeficiente_variacion' => 7.37,
            'num_registros' => 45,
            'num_encuestas' => 15,
            'num_casas_comerciales' => 10,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 27.80,
            'variacion_absoluta' => 0.70,
            'variacion_porcentual' => 2.52,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Ridomil Gold MZ (Fungicida) - Noviembre 2025
        $analisis[] = [
            'tipo_formulario' => 'F-6',
            'producto_id' => 6,
            'producto_nombre' => 'Ridomil Gold MZ',
            'producto_categoria' => 'Fungicidas',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Islay',
            'distrito' => null,
            'precio_promedio' => 52.00,
            'precio_minimo' => 48.00,
            'precio_maximo' => 56.00,
            'precio_mediana' => 52.00,
            'desviacion_estandar' => 2.50,
            'coeficiente_variacion' => 4.81,
            'num_registros' => 28,
            'num_encuestas' => 9,
            'num_casas_comerciales' => 7,
            'num_transportistas' => null,
            'precio_periodo_anterior' => 51.00,
            'variacion_absoluta' => 1.00,
            'variacion_porcentual' => 1.96,
            'tendencia' => 'estable',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // ============================================
        // F-14: ANÁLISIS DE PRECIOS DE TRANSPORTE
        // ============================================

        // Ruta Arequipa - Lima
        $analisis[] = [
            'tipo_formulario' => 'F-14',
            'producto_id' => 0,
            'producto_nombre' => 'Flete Arequipa - Lima (Camión 10 TN)',
            'producto_categoria' => 'Transporte',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 2800.00,
            'precio_minimo' => 2500.00,
            'precio_maximo' => 3200.00,
            'precio_mediana' => 2750.00,
            'desviacion_estandar' => 185.00,
            'coeficiente_variacion' => 6.61,
            'num_registros' => 22,
            'num_encuestas' => 8,
            'num_casas_comerciales' => null,
            'num_transportistas' => 8,
            'precio_periodo_anterior' => 2580.00,
            'variacion_absoluta' => 220.00,
            'variacion_porcentual' => 8.53,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Ruta Arequipa - Cusco
        $analisis[] = [
            'tipo_formulario' => 'F-14',
            'producto_id' => 0,
            'producto_nombre' => 'Flete Arequipa - Cusco (Camión 8 TN)',
            'producto_categoria' => 'Transporte',
            'fecha' => $fecha_actual,
            'año' => $fecha_actual->year,
            'mes' => $fecha_actual->month,
            'semana' => $fecha_actual->weekOfMonth,
            'region' => 'Arequipa',
            'provincia' => 'Arequipa',
            'distrito' => null,
            'precio_promedio' => 1200.00,
            'precio_minimo' => 1100.00,
            'precio_maximo' => 1350.00,
            'precio_mediana' => 1200.00,
            'desviacion_estandar' => 75.00,
            'coeficiente_variacion' => 6.25,
            'num_registros' => 18,
            'num_encuestas' => 6,
            'num_casas_comerciales' => null,
            'num_transportistas' => 6,
            'precio_periodo_anterior' => 1150.00,
            'variacion_absoluta' => 50.00,
            'variacion_porcentual' => 4.35,
            'tendencia' => 'aumento',
            'fecha_calculo' => now(),
            'version' => 1,
        ];

        // Insertar todos los análisis
        foreach ($analisis as $item) {
            DB::table('agri_analisis_precios')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ Se insertaron ' . count($analisis) . ' análisis de precios (Noviembre 2025)');
        $this->command->info('   • F-1 (Maquinaria): 3 análisis');
        $this->command->info('   • F-4 (Fertilizantes): 5 análisis');
        $this->command->info('   • F-6 (Agroquímicos): 3 análisis');
        $this->command->info('   • F-14 (Transporte): 2 análisis');
    }
}
