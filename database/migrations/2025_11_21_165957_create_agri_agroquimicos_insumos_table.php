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
        Schema::create('agri_agroquimicos_insumos', function (Blueprint $table) {
            $table->id();

            // Información del agroquímico
            $table->string('nombre_comercial', 150)->comment('Ej: Abamectina 1.8% EC');
            $table->string('ingrediente_activo', 150)->nullable()->comment('Principio activo');
            $table->enum('tipo', ['acaricida', 'adherente', 'fungicida', 'herbicida', 'insecticida', 'nutriente_foliar', 'regulador_crecimiento'])->comment('Categoría del agroquímico');
            $table->string('codigo', 20)->unique()->nullable()->comment('Código interno SIEA');

            // Composición
            $table->string('concentracion', 50)->nullable()->comment('Ej: 1.8% EC, 720 g/L');
            $table->string('formulacion', 50)->nullable()->comment('EC, SC, WP, etc.');

            // Presentación
            $table->string('presentacion', 100)->default('litro')->comment('litro, kg, galón, etc.');
            $table->decimal('cantidad_presentacion', 8, 2)->default(1)->comment('Cantidad en la presentación');

            // Información regulatoria
            $table->string('registro_senasa', 50)->nullable()->comment('Número de registro SENASA');
            $table->enum('categoria_toxicologica', ['Ia', 'Ib', 'II', 'III', 'IV', 'U'])->nullable()->comment('Clasificación OMS');

            // Descripción y uso
            $table->text('descripcion')->nullable();
            $table->text('cultivos_objetivo')->nullable()->comment('Cultivos en los que se aplica');
            $table->text('plagas_objetivo')->nullable()->comment('Plagas que controla');

            // Estado
            $table->boolean('activo')->default(true);

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipo');
            $table->index('activo');
            $table->index('categoria_toxicologica');
            $table->fullText(['nombre_comercial', 'ingrediente_activo', 'descripcion'], 'ft_agro_busqueda');

            // Evitar duplicados
            $table->unique(['nombre_comercial', 'concentracion', 'cantidad_presentacion'], 'unique_agroquimico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_agroquimicos_insumos');
    }
};
