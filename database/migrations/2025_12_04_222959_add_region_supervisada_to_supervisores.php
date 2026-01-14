<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agri_supervisores_insumos', function (Blueprint $table) {
            // Agregar region_supervisada como alias de region_asignada
            $table->string('region_supervisada', 100)->nullable()->after('provincia_asignada');
            // Agregar fecha_asignacion si no existe
            $table->date('fecha_asignacion')->nullable()->after('region_supervisada');
            // Agregar observaciones si no existe
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agri_supervisores_insumos', function (Blueprint $table) {
            $table->dropColumn(['region_supervisada', 'fecha_asignacion', 'observaciones']);
        });
    }
};
