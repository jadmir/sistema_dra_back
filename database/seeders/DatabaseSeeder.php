<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders del sistema de precios
        $this->call([
            PreGeoUbicacionSeeder::class,
            PreCategoriasSeeder::class,
            PreProductosSeeder::class,
            PreMercadosSeeder::class,
            PreEncuestadoresSeeder::class,
            PreMuestrasSeeder::class,
        ]);
    }
}
