<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['codigo' => 'TUB', 'nombre' => 'Tubérculos', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'HOR', 'nombre' => 'Hortalizas', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'FRU', 'nombre' => 'Frutas', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'CER', 'nombre' => 'Cereales', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'LEG', 'nombre' => 'Legumbres', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'CAR', 'nombre' => 'Carnes', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'LAC', 'nombre' => 'Lácteos', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'HUE', 'nombre' => 'Huevos', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'ACE', 'nombre' => 'Aceites', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'AZU', 'nombre' => 'Azúcares', 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'OTR', 'nombre' => 'Otros', 'usuario_id' => 1, 'estado' => true],
        ];

        DB::table('pre_categorias')->insert($categorias);
    }
}
