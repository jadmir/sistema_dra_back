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
        Schema::create('agri_tipo_maquinaria_insumos', function (Blueprint $table) {
            $table->id();

            // Información del tipo de maquinaria
            $table->string('nombre', 100)->unique()->comment('Ej: Tractor 100-120 HP, Mano obra vareador');
            $table->enum('categoria', ['tractor', 'maquinaria', 'mano_obra', 'yunta'])->comment('Categoría general');
            $table->string('codigo', 20)->unique()->nullable()->comment('Código interno SIEA');

            // Descripción y características
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 50)->default('hora')->comment('hora, día, jornal, etc.');

            // Especificaciones técnicas (JSON)
            $table->json('especificaciones')->nullable()->comment('Ej: {potencia: "100-120 HP", tipo_traccion: "4x4"}');

            // Estado
            $table->boolean('activo')->default(true);

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('categoria');
            $table->index('activo');
            $table->fullText(['nombre', 'descripcion'], 'ft_maq_busqueda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_tipo_maquinaria_insumos');
    }
};
