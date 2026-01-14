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
        // Cambiar columna de SET a TEXT para poder almacenar JSON
        DB::statement('ALTER TABLE agri_encuestadores_insumos MODIFY especializacion TEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a SET si es necesario
        DB::statement("ALTER TABLE agri_encuestadores_insumos MODIFY especializacion SET('maquinaria','fertilizantes','agroquimicos','transporte') NULL");
    }
};
