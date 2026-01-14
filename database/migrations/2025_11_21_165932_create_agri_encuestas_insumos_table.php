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
        Schema::create('agri_encuestas_insumos', function (Blueprint $table) {
            $table->id();

            // Tipo de formulario
            $table->enum('tipo_formulario', ['F-1', 'F-4', 'F-6', 'F-14']);

            // Ubicación política
            $table->string('region', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100);
            $table->string('localidad', 150)->nullable();

            // Período de referencia
            $table->year('anio');
            $table->tinyInteger('mes')->comment('1-12');
            $table->date('fecha_recoleccion');

            // Personal involucrado
            $table->unsignedBigInteger('encuestador_id');
            $table->foreign('encuestador_id')->references('id')->on('agri_encuestadores_insumos')->onDelete('restrict');

            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('agri_supervisores_insumos')->onDelete('set null');

            // Información del informante/fuente
            $table->string('nombre_informante', 150)->nullable();
            $table->string('telefono_informante', 15)->nullable();
            $table->string('fuente_informacion', 100)->nullable()->comment('Ej: Mercado Central, Tienda Agroquímicos ABC');

            // Estado del formulario
            $table->enum('estado', ['borrador', 'enviado', 'validado', 'rechazado'])->default('borrador');
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_validacion')->nullable();

            // Validación del supervisor
            $table->text('observaciones_supervisor')->nullable();
            $table->string('firma_supervisor', 255)->nullable()->comment('Ruta a imagen de firma digital');

            // Observaciones generales
            $table->text('observaciones')->nullable();

            // Auditoría
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipo_formulario');
            $table->index(['region', 'provincia', 'distrito'], 'idx_encuesta_ubicacion');
            $table->index(['anio', 'mes'], 'idx_encuesta_periodo');
            $table->index('encuestador_id');
            $table->index('supervisor_id');
            $table->index('estado');
            $table->index('fecha_recoleccion');

            // Índice único para evitar duplicados
            $table->unique(['tipo_formulario', 'region', 'provincia', 'distrito', 'anio', 'mes', 'encuestador_id'], 'unique_encuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_encuestas_insumos');
    }
};
