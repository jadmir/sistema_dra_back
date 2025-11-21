<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PreMuestrasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hoy = Carbon::today();
        $ayer = Carbon::yesterday();

        $muestras = [
            // ========== HOY - PAPA BLANCA ==========
            // Mercado Mayorista Arequipa (id=1) - MAYORISTA
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 2.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 2.60, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 2, 'precio' => 2.40, 'moneda' => 'PEN', 'procedencia_principal' => 'Cusco', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 1, 'precio' => 2.55, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // Mercado San Camilo (id=4) - MINORISTA
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 3.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 3.60, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 3.55, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 2, 'precio' => 3.40, 'moneda' => 'PEN', 'procedencia_principal' => 'Cusco', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // ========== HOY - TOMATE ==========
            // Mercado Mayorista Arequipa (id=1) - MAYORISTA
            ['mercado_id' => 1, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 5, 'calidad' => 1, 'precio' => 3.00, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 6, 'calidad' => 1, 'precio' => 3.20, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 7, 'calidad' => 2, 'precio' => 2.80, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima-Cañete', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 8, 'calidad' => 1, 'precio' => 3.10, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // Mercado San Camilo (id=4) - MINORISTA
            ['mercado_id' => 4, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 4.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 4.60, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 4.55, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 4, 'encuestador_id' => 2, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 2, 'precio' => 4.20, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima-Cañete', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // ========== HOY - CEBOLLA ==========
            // Mercado Mayorista Arequipa (id=1) - MAYORISTA
            ['mercado_id' => 1, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 9, 'calidad' => 1, 'precio' => 2.00, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 10, 'calidad' => 1, 'precio' => 2.10, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 11, 'calidad' => 2, 'precio' => 1.90, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 12, 'calidad' => 1, 'precio' => 2.05, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // Mercado San Camilo (id=4) - MINORISTA
            ['mercado_id' => 4, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 2.80, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 2.90, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 2.85, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 5, 'encuestador_id' => 3, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 2, 'precio' => 2.70, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // ========== HOY - POLLO ==========
            // Mercado Mayorista Arequipa (id=1) - MAYORISTA
            ['mercado_id' => 1, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 13, 'calidad' => 1, 'precio' => 7.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 14, 'calidad' => 1, 'precio' => 7.60, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 15, 'calidad' => 1, 'precio' => 7.55, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 16, 'calidad' => 1, 'precio' => 7.65, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // Mercado El Palomar (id=5) - MINORISTA
            ['mercado_id' => 5, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 9.00, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 5, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 9.20, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 5, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 9.10, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],
            ['mercado_id' => 5, 'producto_id' => 17, 'encuestador_id' => 4, 'fecha' => $hoy, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 1, 'precio' => 9.15, 'moneda' => 'PEN', 'procedencia_principal' => 'Lima', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $hoy, 'usuario_id' => 1],

            // ========== AYER - PAPA BLANCA (para comparación histórica) ==========
            // Mercado Mayorista Arequipa - MAYORISTA
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 2.45, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 2.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 2.48, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 1, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 1, 'precio' => 2.52, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],

            // Mercado San Camilo - MINORISTA
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 1, 'punto_nro' => 1, 'calidad' => 1, 'precio' => 3.45, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 2, 'punto_nro' => 2, 'calidad' => 1, 'precio' => 3.50, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 3, 'punto_nro' => 3, 'calidad' => 1, 'precio' => 3.48, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
            ['mercado_id' => 4, 'producto_id' => 1, 'encuestador_id' => 1, 'fecha' => $ayer, 'muestra_nro' => 4, 'punto_nro' => 4, 'calidad' => 1, 'precio' => 3.52, 'moneda' => 'PEN', 'procedencia_principal' => 'Arequipa-Majes', 'procedencia_secundaria' => null, 'observaciones' => null, 'validado' => true, 'validado_por' => 1, 'validado_at' => $ayer, 'usuario_id' => 1],
        ];

        DB::table('pre_muestras')->insert($muestras);
    }
}
