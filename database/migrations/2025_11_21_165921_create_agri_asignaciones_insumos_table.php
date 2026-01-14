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
        Schema::create('agri_asignaciones_insumos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('encuestador_id');
            $table->foreign('encuestador_id')->references('id')->on('agri_encuestadores_insumos')->onDelete('cascade');

            $table->unsignedBigInteger('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('agri_supervisores_insumos')->onDelete('cascade');

            // Ubicación asignada
            $table->string('region', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100)->nullable()->comment('NULL = toda la provincia');

            // Tipo de formulario asignado
            $table->enum('tipo_formulario', ['F-1', 'F-4', 'F-6', 'F-14']);

            // Vigencia de la asignación
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable()->comment('NULL = asignación vigente');
            $table->enum('estado', ['activa', 'suspendida', 'finalizada'])->default('activa');

            // Restricciones adicionales
            $table->json('restricciones')->nullable()->comment('Ej: días de recolección, mercados específicos');

            // Auditoría
            $table->unsignedBigInteger('asignado_por')->nullable();
            $table->foreign('asignado_por')->references('id')->on('usuarios')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('encuestador_id');
            $table->index('supervisor_id');
            $table->index(['region', 'provincia', 'distrito'], 'idx_asignacion_ubicacion');
            $table->index('tipo_formulario');
            $table->index('estado');
            $table->index(['fecha_inicio', 'fecha_fin'], 'idx_asignacion_fechas');

            // Índice único para evitar asignaciones duplicadas
            $table->unique(['encuestador_id', 'supervisor_id', 'region', 'provincia', 'tipo_formulario', 'fecha_inicio'], 'unique_asignacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_asignaciones_insumos');
    }
};
