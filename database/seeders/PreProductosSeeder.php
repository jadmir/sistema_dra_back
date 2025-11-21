<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // Tubérculos
            ['codigo' => 'TUB-001', 'nombre' => 'Papa Blanca', 'categoria_id' => 1, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'TUB-002', 'nombre' => 'Papa Amarilla', 'categoria_id' => 1, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'TUB-003', 'nombre' => 'Camote', 'categoria_id' => 1, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Hortalizas
            ['codigo' => 'HOR-001', 'nombre' => 'Tomate', 'categoria_id' => 2, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'HOR-002', 'nombre' => 'Cebolla', 'categoria_id' => 2, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'HOR-003', 'nombre' => 'Zanahoria', 'categoria_id' => 2, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'HOR-004', 'nombre' => 'Lechuga', 'categoria_id' => 2, 'unidad_medida' => 'unidad', 'equivalencia_kg' => 0.30, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Frutas
            ['codigo' => 'FRU-001', 'nombre' => 'Manzana', 'categoria_id' => 3, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'FRU-002', 'nombre' => 'Plátano', 'categoria_id' => 3, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'FRU-003', 'nombre' => 'Naranja', 'categoria_id' => 3, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'FRU-004', 'nombre' => 'Limón', 'categoria_id' => 3, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Cereales
            ['codigo' => 'CER-001', 'nombre' => 'Arroz', 'categoria_id' => 4, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'CER-002', 'nombre' => 'Maíz Choclo', 'categoria_id' => 4, 'unidad_medida' => 'unidad', 'equivalencia_kg' => 0.25, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'CER-003', 'nombre' => 'Quinua', 'categoria_id' => 4, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Legumbres
            ['codigo' => 'LEG-001', 'nombre' => 'Frijol Canario', 'categoria_id' => 5, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'LEG-002', 'nombre' => 'Lenteja', 'categoria_id' => 5, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Carnes
            ['codigo' => 'CAR-001', 'nombre' => 'Pollo Entero', 'categoria_id' => 6, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'CAR-002', 'nombre' => 'Carne de Res', 'categoria_id' => 6, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Lácteos
            ['codigo' => 'LAC-001', 'nombre' => 'Leche Fresca', 'categoria_id' => 7, 'unidad_medida' => 'litro', 'equivalencia_kg' => 1.03, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
            ['codigo' => 'LAC-002', 'nombre' => 'Queso Fresco', 'categoria_id' => 7, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Huevos
            ['codigo' => 'HUE-001', 'nombre' => 'Huevos de Gallina', 'categoria_id' => 8, 'unidad_medida' => 'unidad', 'equivalencia_kg' => 0.06, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Aceites
            ['codigo' => 'ACE-001', 'nombre' => 'Aceite Vegetal', 'categoria_id' => 9, 'unidad_medida' => 'litro', 'equivalencia_kg' => 0.92, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],

            // Azúcares
            ['codigo' => 'AZU-001', 'nombre' => 'Azúcar Blanca', 'categoria_id' => 10, 'unidad_medida' => 'kg', 'equivalencia_kg' => 1.00, 'nombre_cientifico' => null, 'usuario_id' => 1, 'estado' => true],
        ];

        DB::table('pre_productos')->insert($productos);
    }
}
