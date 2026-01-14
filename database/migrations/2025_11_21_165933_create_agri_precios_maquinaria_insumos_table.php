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
        Schema::create('agri_precios_maquinaria_insumos', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta (cabecera F-1)
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('agri_encuestas_insumos')->onDelete('cascade');

            // Tipo de maquinaria
            $table->unsignedBigInteger('tipo_maquinaria_id');
            $table->foreign('tipo_maquinaria_id')->references('id')->on('agri_tipo_maquinaria_insumos')->onDelete('restrict');

            // Precio
            $table->decimal('precio_alquiler', 10, 2)->comment('Precio en soles por unidad de medida');
            $table->string('unidad_medida', 50)->default('hora');

            // Información adicional del proveedor/mercado
            $table->string('nombre_proveedor', 150)->nullable();
            $table->string('direccion_proveedor', 255)->nullable();
            $table->string('telefono_proveedor', 15)->nullable();

            // Condiciones del alquiler
            $table->text('condiciones')->nullable()->comment('Ej: Incluye operador, combustible aparte');

            // Disponibilidad
            $table->enum('disponibilidad', ['alta', 'media', 'baja'])->default('media');

            // Observaciones específicas
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('encuesta_id');
            $table->index('tipo_maquinaria_id');
            $table->index('precio_alquiler');

            // Evitar duplicados en misma encuesta
            $table->unique(['encuesta_id', 'tipo_maquinaria_id', 'nombre_proveedor'], 'unique_precio_maquinaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_precios_maquinaria_insumos');
    }
};
