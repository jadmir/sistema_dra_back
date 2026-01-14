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
        Schema::create('agri_precios_transporte_insumos', function (Blueprint $table) {
            $table->id();

            // Relación con encuesta (cabecera F-14)
            $table->unsignedBigInteger('encuesta_id');
            $table->foreign('encuesta_id')->references('id')->on('agri_encuestas_insumos')->onDelete('cascade');

            // Producto transportado
            $table->string('producto', 150)->comment('Ej: Papa, Maíz, Leche, Ganado vacuno');
            $table->enum('tipo_producto', ['agricola', 'pecuario', 'forestal', 'otro'])->default('agricola');

            // Ruta de transporte
            $table->string('origen', 150)->comment('Lugar de origen');
            $table->string('destino', 150)->comment('Lugar de destino');
            $table->decimal('distancia_km', 8, 2)->nullable()->comment('Distancia aproximada en km');

            // Tipo de transporte
            $table->enum('tipo_vehiculo', ['camion_pequeño', 'camion_mediano', 'camion_grande', 'trailer', 'camioneta', 'otro'])->nullable();
            $table->enum('tipo_camino', ['asfaltado', 'afirmado', 'trocha'])->nullable();

            // Precio
            $table->decimal('precio_flete', 10, 2)->comment('Precio del flete en soles');
            $table->enum('unidad_cobro', ['viaje', 'tonelada', 'quintal', 'unidad', 'hora'])->default('viaje');
            $table->decimal('capacidad_carga', 10, 2)->nullable()->comment('Capacidad del vehículo en unidad_cobro');
            $table->decimal('precio_por_tonelada', 10, 2)->nullable()->comment('Calculado si aplica');

            // Información del transportista
            $table->string('nombre_transportista', 150)->nullable();
            $table->string('telefono_transportista', 15)->nullable();
            $table->string('empresa_transporte', 150)->nullable();

            // Condiciones del servicio
            $table->text('condiciones')->nullable()->comment('Ej: Incluye carga/descarga, tiempo estimado');
            $table->enum('disponibilidad', ['alta', 'media', 'baja'])->default('media');
            $table->enum('frecuencia', ['diaria', 'semanal', 'quincenal', 'mensual', 'eventual'])->default('eventual');

            // Observaciones específicas
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('encuesta_id');
            $table->index('producto');
            $table->index('tipo_producto');
            $table->index(['origen', 'destino'], 'idx_transporte_ruta');
            $table->index('tipo_vehiculo');
            $table->index('precio_flete');
            $table->fullText(['producto', 'origen', 'destino'], 'ft_trans_ruta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_precios_transporte_insumos');
    }
};
