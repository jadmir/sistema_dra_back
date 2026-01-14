<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la columna ya existe
        $columnExists = DB::select("SHOW COLUMNS FROM agri_supervisores_insumos WHERE Field = 'provincias_asignadas'");

        if (empty($columnExists)) {
            // La columna NO existe, agregarla como TEXT después de provincia_asignada
            DB::statement('ALTER TABLE agri_supervisores_insumos ADD COLUMN provincias_asignadas TEXT NULL AFTER provincia_asignada');
        } else {
            // La columna existe, cambiarla a TEXT (por si es SET u otro tipo)
            DB::statement('ALTER TABLE agri_supervisores_insumos MODIFY COLUMN provincias_asignadas TEXT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agri_supervisores_insumos', function (Blueprint $table) {
            $table->dropColumn('provincias_asignadas');
        });
    }
};
