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
        Schema::create('agri_precios_agroquimicos_insumos', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta (cabecera F-6)
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('agri_encuestas_insumos')->onDelete('cascade');

            // Agroquímico
            $table->unsignedBigInteger('agroquimico_id');
            $table->foreign('agroquimico_id')->references('id')->on('agri_agroquimicos_insumos')->onDelete('restrict');

            // Precio
            $table->decimal('precio_venta', 10, 2)->comment('Precio en soles por presentación');
            $table->string('presentacion', 100)->default('litro');
            $table->decimal('precio_unitario', 10, 2)->nullable()->comment('Precio por litro/kg calculado');

            // Información del punto de venta
            $table->string('nombre_tienda', 150)->nullable();
            $table->string('direccion_tienda', 255)->nullable();
            $table->string('telefono_tienda', 15)->nullable();
            $table->enum('tipo_establecimiento', ['tienda_especializada', 'distribuidora', 'cooperativa', 'veterinaria', 'otro'])->nullable();

            // Stock y disponibilidad
            $table->enum('disponibilidad', ['alta', 'media', 'baja', 'agotado'])->default('media');
            $table->integer('stock_aproximado')->nullable()->comment('Unidades disponibles');

            // Marca y origen
            $table->string('marca', 100)->nullable();
            $table->string('laboratorio', 150)->nullable()->comment('Laboratorio fabricante');
            $table->string('origen', 50)->nullable()->comment('Nacional, importado');

            // Fecha de vencimiento
            $table->date('fecha_vencimiento')->nullable();

            // Observaciones específicas
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('encuesta_id');
            $table->index('agroquimico_id');
            $table->index('precio_venta');
            $table->index('disponibilidad');
            $table->index('fecha_vencimiento');

            // Evitar duplicados en misma encuesta
            $table->unique(['encuesta_id', 'agroquimico_id', 'nombre_tienda', 'marca'], 'unique_precio_agroquimico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_precios_agroquimicos_insumos');
    }
};
