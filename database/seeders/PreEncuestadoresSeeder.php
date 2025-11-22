<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreEncuestadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encuestadores = [
            [
                'codigo' => 'ENC-001',
                'nombre' => 'Juan Carlos Pérez López',
                'dni' => '43567890',
                'telefono' => '987654321',
                'email' => 'jperez@dra.gob.pe',
                'usuario_id' => null,
                'estado' => true
            ],
            [
                'codigo' => 'ENC-002',
                'nombre' => 'María Elena Gonzales Quispe',
                'dni' => '45678901',
                'telefono' => '987123456',
                'email' => 'mgonzales@dra.gob.pe',
                'usuario_id' => null,
                'estado' => true
            ],
            [
                'codigo' => 'ENC-003',
                'nombre' => 'Carlos Alberto Mamani Huanca',
                'dni' => '47890123',
                'telefono' => '986789012',
                'email' => 'cmamani@dra.gob.pe',
                'usuario_id' => null,
                'estado' => true
            ],
            [
                'codigo' => 'ENC-004',
                'nombre' => 'Rosa María Condori Apaza',
                'dni' => '48901234',
                'telefono' => '985678901',
                'email' => 'rcondori@dra.gob.pe',
                'usuario_id' => null,
                'estado' => true
            ],
            [
                'codigo' => 'ENC-005',
                'nombre' => 'Luis Fernando Chávez Ramos',
                'dni' => '49012345',
                'telefono' => '984567890',
                'email' => 'lchavez@dra.gob.pe',
                'usuario_id' => null,
                'estado' => true
            ],
        ];

        DB::table('pre_encuestadores')->insert($encuestadores);
    }
}
