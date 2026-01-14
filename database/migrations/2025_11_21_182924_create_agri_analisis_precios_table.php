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
        Schema::create('agri_analisis_precios', function (Blueprint $table) {
            $table->id();

            // Tipo de insumo y referencia
            $table->enum('tipo_formulario', ['F-1', 'F-4', 'F-6', 'F-14']);
            $table->unsignedBigInteger('producto_id')->comment('ID del tipo_maquinaria, fertilizante, agroquimico o NULL para transporte');
            $table->string('producto_nombre', 255);
            $table->string('producto_categoria', 100)->nullable();

            // Fecha del análisis
            $table->date('fecha');
            $table->integer('año');
            $table->integer('mes');
            $table->integer('semana')->nullable();

            // Ubicación
            $table->string('region', 100)->default('Arequipa');
            $table->string('provincia', 100)->nullable();
            $table->string('distrito', 100)->nullable();

            // Estadísticas de precios
            $table->decimal('precio_promedio', 10, 2);
            $table->decimal('precio_minimo', 10, 2);
            $table->decimal('precio_maximo', 10, 2);
            $table->decimal('precio_mediana', 10, 2)->nullable();
            $table->decimal('desviacion_estandar', 10, 2)->nullable();
            $table->decimal('coeficiente_variacion', 5, 2)->nullable()->comment('(desv_std / promedio) * 100');

            // Conteo de registros
            $table->integer('num_registros')->comment('Total de registros de precios');
            $table->integer('num_encuestas')->comment('Total de encuestas que contienen este producto');
            $table->integer('num_casas_comerciales')->nullable()->comment('Para F-4, F-6');
            $table->integer('num_transportistas')->nullable()->comment('Para F-14');

            // Comparación con período anterior
            $table->decimal('precio_periodo_anterior', 10, 2)->nullable();
            $table->decimal('variacion_absoluta', 10, 2)->nullable();
            $table->decimal('variacion_porcentual', 8, 2)->nullable();
            $table->enum('tendencia', ['aumento', 'disminución', 'estable'])->nullable();

            // Metadatos
            $table->timestamp('fecha_calculo');
            $table->integer('version')->default(1)->comment('Versión del cálculo si se recalcula');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipo_formulario');
            $table->index('producto_id');
            $table->index('fecha');
            $table->index(['año', 'mes'], 'idx_periodo');
            $table->index(['region', 'provincia', 'distrito'], 'idx_ubicacion');
            $table->index('tendencia');

            // Índice único para evitar duplicados
            $table->unique(['tipo_formulario', 'producto_id', 'fecha', 'provincia', 'distrito'], 'unique_analisis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_analisis_precios');
    }
};
