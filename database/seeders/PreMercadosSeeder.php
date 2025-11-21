<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreMercadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mercados = [
            // MAYORISTAS
            [
                'nombre' => 'Mercado Mayorista de Arequipa',
                'direccion' => 'Av. Aviación s/n',
                'zona' => 'Zona Industrial',
                'tipo' => 'MAYORISTA',
                'ubicacion_id' => 1, // Arequipa - Cercado
                'latitud' => -16.409047,
                'longitud' => -71.537451,
                'telefono' => '054-123456',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado Mayorista La Parada',
                'direccion' => 'Av. Los Incas 1245',
                'zona' => 'La Victoria',
                'tipo' => 'MAYORISTA',
                'ubicacion_id' => 11, // Lima - La Victoria
                'latitud' => -12.070381,
                'longitud' => -77.016389,
                'telefono' => '01-987654',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Terminal Pesquero Cusco',
                'direccion' => 'Av. Grau 890',
                'zona' => 'Centro',
                'tipo' => 'MAYORISTA',
                'ubicacion_id' => 5, // Cusco - Cusco
                'latitud' => -13.531050,
                'longitud' => -71.967463,
                'telefono' => '084-234567',
                'usuario_id' => 1,
                'estado' => true
            ],

            // MINORISTAS
            [
                'nombre' => 'Mercado San Camilo',
                'direccion' => 'Calle Perú 302',
                'zona' => 'Centro Histórico',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 1, // Arequipa - Cercado
                'latitud' => -16.398866,
                'longitud' => -71.536961,
                'telefono' => '054-215678',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado El Palomar',
                'direccion' => 'Av. Ejército 890',
                'zona' => 'Yanahuara',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 3, // Arequipa - Yanahuara
                'latitud' => -16.394723,
                'longitud' => -71.546081,
                'telefono' => '054-345789',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado Wanchaq',
                'direccion' => 'Av. Tomasa Ttito Condemayta',
                'zona' => 'Wanchaq',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 6, // Cusco - Wanchaq
                'latitud' => -13.517590,
                'longitud' => -71.976242,
                'telefono' => '084-456890',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado Belén Puno',
                'direccion' => 'Jr. Cajamarca 145',
                'zona' => 'Centro',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 8, // Puno - Puno
                'latitud' => -15.839033,
                'longitud' => -70.021743,
                'telefono' => '051-567901',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado Unión y Dignidad',
                'direccion' => 'Av. Sanchez Cerro 567',
                'zona' => 'Juliaca',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 10, // Puno - San Román - Juliaca
                'latitud' => -15.500000,
                'longitud' => -70.133333,
                'telefono' => '051-678012',
                'usuario_id' => 1,
                'estado' => true
            ],
            [
                'nombre' => 'Mercado 2 de Mayo',
                'direccion' => 'Av. 2 de Mayo 234',
                'zona' => 'Centro',
                'tipo' => 'MINORISTA',
                'ubicacion_id' => 14, // Tacna - Tacna
                'latitud' => -18.006395,
                'longitud' => -70.246200,
                'telefono' => '052-789123',
                'usuario_id' => 1,
                'estado' => true
            ],
        ];

        DB::table('pre_mercados')->insert($mercados);
    }
}
