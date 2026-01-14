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
        Schema::create('agri_fertilizantes_insumos', function (Blueprint $table) {
            $table->id();

            // Información del fertilizante
            $table->string('nombre_comercial', 150)->comment('Ej: Urea, Nitrato de amonio');
            $table->string('nombre_generico', 100)->nullable();
            $table->enum('tipo', ['nitrogenado', 'fosfatado', 'potasico', 'compuesto', 'organico'])->comment('Tipo según composición');
            $table->string('codigo', 20)->unique()->nullable()->comment('Código interno SIEA');

            // Composición química
            $table->string('formula', 50)->nullable()->comment('Ej: 46-0-0, 12-12-12');
            $table->decimal('porcentaje_nitrogeno', 5, 2)->nullable();
            $table->decimal('porcentaje_fosforo', 5, 2)->nullable();
            $table->decimal('porcentaje_potasio', 5, 2)->nullable();

            // Presentación
            $table->string('presentacion', 100)->default('saco')->comment('saco, granel, bolsa, etc.');
            $table->decimal('peso_presentacion', 8, 2)->default(50)->comment('Peso en kg');

            // Descripción
            $table->text('descripcion')->nullable();

            // Estado
            $table->boolean('activo')->default(true);

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipo');
            $table->index('activo');
            $table->index('formula');
            $table->fullText(['nombre_comercial', 'nombre_generico', 'descripcion'], 'ft_fert_busqueda');

            // Evitar duplicados
            $table->unique(['nombre_comercial', 'formula', 'peso_presentacion'], 'unique_fertilizante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_fertilizantes_insumos');
    }
};
