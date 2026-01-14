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
        Schema::create('agri_precios_fertilizantes_insumos', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta (cabecera F-4)
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('agri_encuestas_insumos')->onDelete('cascade');

            // Fertilizante
            $table->unsignedBigInteger('fertilizante_id');
            $table->foreign('fertilizante_id')->references('id')->on('agri_fertilizantes_insumos')->onDelete('restrict');

            // Precio
            $table->decimal('precio_venta', 10, 2)->comment('Precio en soles por presentación');
            $table->string('presentacion', 100)->default('saco 50kg');
            $table->decimal('precio_por_kg', 10, 2)->nullable()->comment('Calculado automáticamente');

            // Información del punto de venta
            $table->string('nombre_tienda', 150)->nullable();
            $table->string('direccion_tienda', 255)->nullable();
            $table->string('telefono_tienda', 15)->nullable();
            $table->enum('tipo_establecimiento', ['tienda_especializada', 'distribuidora', 'cooperativa', 'mercado', 'otro'])->nullable();

            // Stock y disponibilidad
            $table->enum('disponibilidad', ['alta', 'media', 'baja', 'agotado'])->default('media');
            $table->integer('stock_aproximado')->nullable()->comment('Unidades disponibles');

            // Marca y origen
            $table->string('marca', 100)->nullable();
            $table->string('origen', 50)->nullable()->comment('Nacional, importado');

            // Observaciones específicas
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('encuesta_id');
            $table->index('fertilizante_id');
            $table->index('precio_venta');
            $table->index('disponibilidad');

            // Evitar duplicados en misma encuesta
            $table->unique(['encuesta_id', 'fertilizante_id', 'nombre_tienda', 'marca'], 'unique_precio_fertilizante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_precios_fertilizantes_insumos');
    }
};
