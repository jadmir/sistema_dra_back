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
        Schema::create('pre_reporte_comparativo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('pre_productos');
            $table->foreignId('ubicacion_id')->nullable()->constrained('pre_geo_ubicacion')->comment('Opcional: para reportes por región');
            $table->date('fecha');

            // Datos MAYORISTAS
            $table->decimal('precio_mayorista_promedio', 10, 2)->nullable();
            $table->decimal('precio_mayorista_minimo', 10, 2)->nullable();
            $table->decimal('precio_mayorista_maximo', 10, 2)->nullable();
            $table->integer('num_mercados_mayoristas')->nullable()->comment('Cantidad de mercados mayoristas');
            $table->integer('num_muestras_mayoristas')->nullable()->comment('Total de muestras mayoristas');

            // Datos MINORISTAS
            $table->decimal('precio_minorista_promedio', 10, 2)->nullable();
            $table->decimal('precio_minorista_minimo', 10, 2)->nullable();
            $table->decimal('precio_minorista_maximo', 10, 2)->nullable();
            $table->integer('num_mercados_minoristas')->nullable()->comment('Cantidad de mercados minoristas');
            $table->integer('num_muestras_minoristas')->nullable()->comment('Total de muestras minoristas');

            // Variación calculada
            $table->decimal('variacion_porcentual', 8, 2)->nullable()->comment('((Min - May) / May) * 100');

            $table->timestamps();
            $table->softDeletes();

            // Índice único para evitar duplicados
            $table->unique(['producto_id', 'fecha', 'ubicacion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_reporte_comparativo');
    }
};
