<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreGeoUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ubicaciones = [
            // Arequipa
            ['departamento' => 'Arequipa', 'provincia' => 'Arequipa', 'distrito' => 'Cercado', 'estado' => true],
            ['departamento' => 'Arequipa', 'provincia' => 'Arequipa', 'distrito' => 'Cayma', 'estado' => true],
            ['departamento' => 'Arequipa', 'provincia' => 'Arequipa', 'distrito' => 'Yanahuara', 'estado' => true],
            ['departamento' => 'Arequipa', 'provincia' => 'Camaná', 'distrito' => 'Camaná', 'estado' => true],

            // Cusco
            ['departamento' => 'Cusco', 'provincia' => 'Cusco', 'distrito' => 'Cusco', 'estado' => true],
            ['departamento' => 'Cusco', 'provincia' => 'Cusco', 'distrito' => 'Wanchaq', 'estado' => true],
            ['departamento' => 'Cusco', 'provincia' => 'La Convención', 'distrito' => 'Santa Ana', 'estado' => true],

            // Puno
            ['departamento' => 'Puno', 'provincia' => 'Puno', 'distrito' => 'Puno', 'estado' => true],
            ['departamento' => 'Puno', 'provincia' => 'Puno', 'distrito' => 'Juliaca', 'estado' => true],
            ['departamento' => 'Puno', 'provincia' => 'San Román', 'distrito' => 'Juliaca', 'estado' => true],

            // Lima
            ['departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'La Victoria', 'estado' => true],
            ['departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Los Olivos', 'estado' => true],
            ['departamento' => 'Lima', 'provincia' => 'Cañete', 'distrito' => 'San Vicente', 'estado' => true],

            // Tacna
            ['departamento' => 'Tacna', 'provincia' => 'Tacna', 'distrito' => 'Tacna', 'estado' => true],
            ['departamento' => 'Tacna', 'provincia' => 'Tacna', 'distrito' => 'Alto de la Alianza', 'estado' => true],
        ];

        DB::table('pre_geo_ubicacion')->insert($ubicaciones);
    }
}
