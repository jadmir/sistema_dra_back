<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EncuestasEjemploSeeder extends Seeder
{
    /**
     * Seed encuestas de ejemplo para testing y reportes
     */
    public function run(): void
    {
        $now = Carbon::now();
        $mesActual = $now->month;
        $añoActual = $now->year;

        // Obtener IDs de encuestadores y supervisores
        $encuestadores = DB::table('agri_encuestadores_insumos')
            ->where('estado', 'activo')
            ->pluck('id')
            ->toArray();

        $supervisores = DB::table('agri_supervisores_insumos')
            ->where('estado', 'activo')
            ->pluck('id')
            ->toArray();

        if (empty($encuestadores) || empty($supervisores)) {
            $this->command->error('❌ Debe ejecutar primero EncuestadoresSeeder y SupervisoresSeeder');
            return;
        }

        $encuestas = [];

        // ============================================
        // F-1: MAQUINARIA AGRÍCOLA - 15 encuestas
        // ============================================
        $fechaF1 = Carbon::create($añoActual, $mesActual, 5);
        $distritosLima = ['LA VICTORIA', 'SAN JUAN DE MIRAFLORES', 'CERCADO DE LIMA'];
        $distritosArequipa = ['CERCADO', 'CAYMA', 'YANAHUARA'];
        $distritosLibertad = ['MOCHE', 'TRUJILLO', 'LAREDO'];

        for ($i = 1; $i <= 15; $i++) {
            $encuestas[] = [
                'tipo_formulario' => 'F-1',
                'region' => $i <= 5 ? 'LIMA METROPOLITANA' : ($i <= 10 ? 'AREQUIPA' : 'LA LIBERTAD'),
                'provincia' => $i <= 5 ? 'LIMA' : ($i <= 10 ? 'AREQUIPA' : 'TRUJILLO'),
                'distrito' => $i <= 5 ? $distritosLima[($i - 1) % 3] : ($i <= 10 ? $distritosArequipa[($i - 6) % 3] : $distritosLibertad[($i - 11) % 3]),
                'localidad' => 'Casa Comercial ' . $i,
                'anio' => $añoActual,
                'mes' => $mesActual,
                'fecha_recoleccion' => $fechaF1->copy()->addDays($i - 1),
                'encuestador_id' => $encuestadores[($i - 1) % count($encuestadores)],
                'supervisor_id' => $supervisores[($i - 1) % count($supervisores)],
                'nombre_informante' => 'Vendedor ' . $i,
                'telefono_informante' => '9' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'fuente_informacion' => 'Casa comercial de maquinaria',
                'estado' => $i <= 12 ? 'validado' : ($i == 13 ? 'enviado' : 'borrador'),
                'fecha_envio' => $i >= 13 ? $fechaF1->copy()->addDays($i) : $fechaF1->copy()->addDays($i),
                'fecha_validacion' => $i <= 12 ? $fechaF1->copy()->addDays($i + 1) : null,
                'observaciones_supervisor' => $i <= 12 ? 'Datos verificados correctamente' : null,
                'observaciones' => null,
                'created_at' => $fechaF1->copy()->addDays($i - 1),
                'updated_at' => $fechaF1->copy()->addDays($i),
            ];
        }

        // ============================================
        // F-4: FERTILIZANTES - 20 encuestas
        // ============================================
        $fechaF4 = Carbon::create($añoActual, $mesActual, 8);
        $distritosLimaF4 = ['SAN JUAN DE LURIGANCHO', 'SAN MARTIN DE PORRES', 'COMAS', 'INDEPENDENCIA'];
        $distritosArequipaF4 = ['MIRAFLORES', 'PAUCARPATA', 'CERRO COLORADO'];
        $distritosCusco = ['WANCHAQ', 'SANTIAGO', 'SAN SEBASTIAN'];

        for ($i = 1; $i <= 20; $i++) {
            $encuestas[] = [
                'tipo_formulario' => 'F-4',
                'region' => $i <= 7 ? 'LIMA METROPOLITANA' : ($i <= 14 ? 'AREQUIPA' : 'CUSCO'),
                'provincia' => $i <= 7 ? 'LIMA' : ($i <= 14 ? 'AREQUIPA' : 'CUSCO'),
                'distrito' => $i <= 7 ? $distritosLimaF4[($i - 1) % 4] : ($i <= 14 ? $distritosArequipaF4[($i - 8) % 3] : $distritosCusco[($i - 15) % 3]),
                'localidad' => 'Distribuidor Agrícola ' . $i,
                'anio' => $añoActual,
                'mes' => $mesActual,
                'fecha_recoleccion' => $fechaF4->copy()->addDays($i - 1),
                'encuestador_id' => $encuestadores[($i - 1) % count($encuestadores)],
                'supervisor_id' => $supervisores[($i - 1) % count($supervisores)],
                'nombre_informante' => 'Gerente ' . $i,
                'telefono_informante' => '9' . str_pad(100 + $i, 8, '0', STR_PAD_LEFT),
                'fuente_informacion' => 'Tienda de fertilizantes',
                'estado' => $i <= 17 ? 'validado' : ($i == 18 ? 'enviado' : 'rechazado'),
                'fecha_envio' => $i >= 18 ? $fechaF4->copy()->addDays($i) : $fechaF4->copy()->addDays($i),
                'fecha_validacion' => $i <= 17 ? $fechaF4->copy()->addDays($i + 1) : ($i == 19 ? $fechaF4->copy()->addDays($i + 1) : null),
                'observaciones_supervisor' => $i <= 17 ? 'Información completa' : ($i == 19 ? 'Precios inconsistentes, corregir' : null),
                'observaciones' => null,
                'created_at' => $fechaF4->copy()->addDays($i - 1),
                'updated_at' => $fechaF4->copy()->addDays($i),
            ];
        }

        // ============================================
        // F-6: AGROQUÍMICOS - 18 encuestas
        // ============================================
        $fechaF6 = Carbon::create($añoActual, $mesActual, 12);
        $distritosLimaF6 = ['CERCADO', 'BREÑA', 'PUEBLO LIBRE'];
        $distritosIca = ['PARCONA', 'SUBTANJALLA', 'LOS AQUIJES'];
        $distritosPiura = ['CASTILLA', '26 DE OCTUBRE', 'CATACAOS'];

        for ($i = 1; $i <= 18; $i++) {
            $encuestas[] = [
                'tipo_formulario' => 'F-6',
                'region' => $i <= 6 ? 'LIMA METROPOLITANA' : ($i <= 12 ? 'ICA' : 'PIURA'),
                'provincia' => $i <= 6 ? 'LIMA' : ($i <= 12 ? 'ICA' : 'PIURA'),
                'distrito' => $i <= 6 ? $distritosLimaF6[($i - 1) % 3] : ($i <= 12 ? $distritosIca[($i - 7) % 3] : $distritosPiura[($i - 13) % 3]),
                'localidad' => 'Agropecuaria ' . $i,
                'anio' => $añoActual,
                'mes' => $mesActual,
                'fecha_recoleccion' => $fechaF6->copy()->addDays($i - 1),
                'encuestador_id' => $encuestadores[($i - 1) % count($encuestadores)],
                'supervisor_id' => $supervisores[($i - 1) % count($supervisores)],
                'nombre_informante' => 'Responsable ' . $i,
                'telefono_informante' => '9' . str_pad(200 + $i, 8, '0', STR_PAD_LEFT),
                'fuente_informacion' => 'Distribuidor de agroquímicos',
                'estado' => $i <= 15 ? 'validado' : 'enviado',
                'fecha_envio' => $fechaF6->copy()->addDays($i),
                'fecha_validacion' => $i <= 15 ? $fechaF6->copy()->addDays($i + 1) : null,
                'observaciones_supervisor' => $i <= 15 ? 'Aprobado' : null,
                'observaciones' => null,
                'created_at' => $fechaF6->copy()->addDays($i - 1),
                'updated_at' => $fechaF6->copy()->addDays($i),
            ];
        }

        // ============================================
        // F-14: TRANSPORTE - 12 encuestas
        // ============================================
        $fechaF14 = Carbon::create($añoActual, $mesActual, 15);
        $distritosLimaF14 = ['ATE', 'SANTA ANITA', 'EL AGUSTINO'];
        $distritosJunin = ['EL TAMBO', 'CHILCA', 'HUANCÁN'];
        $distritosLambayeque = ['JOSÉ LEONARDO ORTIZ', 'LA VICTORIA', 'PIMENTEL'];

        for ($i = 1; $i <= 12; $i++) {
            $encuestas[] = [
                'tipo_formulario' => 'F-14',
                'region' => $i <= 4 ? 'LIMA METROPOLITANA' : ($i <= 8 ? 'JUNÍN' : 'LAMBAYEQUE'),
                'provincia' => $i <= 4 ? 'LIMA' : ($i <= 8 ? 'HUANCAYO' : 'CHICLAYO'),
                'distrito' => $i <= 4 ? $distritosLimaF14[($i - 1) % 3] : ($i <= 8 ? $distritosJunin[($i - 5) % 3] : $distritosLambayeque[($i - 9) % 3]),
                'localidad' => 'Empresa Transporte ' . $i,
                'anio' => $añoActual,
                'mes' => $mesActual,
                'fecha_recoleccion' => $fechaF14->copy()->addDays($i - 1),
                'encuestador_id' => $encuestadores[($i - 1) % count($encuestadores)],
                'supervisor_id' => $supervisores[($i - 1) % count($supervisores)],
                'nombre_informante' => 'Transportista ' . $i,
                'telefono_informante' => '9' . str_pad(300 + $i, 8, '0', STR_PAD_LEFT),
                'fuente_informacion' => 'Empresa de transporte',
                'estado' => $i <= 10 ? 'validado' : 'enviado',
                'fecha_envio' => $fechaF14->copy()->addDays($i),
                'fecha_validacion' => $i <= 10 ? $fechaF14->copy()->addDays($i + 1) : null,
                'observaciones_supervisor' => $i <= 10 ? 'Validado' : null,
                'observaciones' => null,
                'created_at' => $fechaF14->copy()->addDays($i - 1),
                'updated_at' => $fechaF14->copy()->addDays($i),
            ];
        }

        // Insertar todas las encuestas
        DB::table('agri_encuestas_insumos')->insert($encuestas);

        $this->command->info('✅ Encuestas de ejemplo creadas exitosamente:');
        $this->command->info('   📝 F-1 Maquinaria: 15 encuestas (12 validadas, 1 enviada, 2 borrador)');
        $this->command->info('   📝 F-4 Fertilizantes: 20 encuestas (17 validadas, 1 enviada, 2 rechazada)');
        $this->command->info('   📝 F-6 Agroquímicos: 18 encuestas (15 validadas, 3 enviadas)');
        $this->command->info('   📝 F-14 Transporte: 12 encuestas (10 validadas, 2 enviadas)');
        $this->command->info('   📊 TOTAL: 65 encuestas de ejemplo');
    }
}
