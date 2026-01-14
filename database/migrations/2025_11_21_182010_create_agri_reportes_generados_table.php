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
        Schema::create('agri_reportes_generados', function (Blueprint $table) {
            $table->id();

            // Tipo de reporte
            $table->enum('tipo_reporte', [
                'precios_maquinaria',
                'precios_fertilizantes',
                'precios_agroquimicos',
                'precios_transporte',
                'comparativo_periodos',
                'tendencias',
                'productividad_encuestadores',
                'cumplimiento_metas',
                'supervisor_procesamiento',
                'dashboard_supervisor',
                'dashboard_general',
                'cobertura_territorial'
            ]);

            // Parámetros del reporte (JSON)
            $table->json('parametros')->comment('Filtros aplicados: fechas, provincias, productos, etc.');

            // Período del reporte
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();

            // Resultado del reporte (JSON)
            $table->json('resultado')->nullable()->comment('Datos del reporte generado');

            // Archivo generado (si aplica)
            $table->string('archivo_path', 500)->nullable()->comment('Ruta del PDF/Excel generado');
            $table->enum('formato', ['json', 'pdf', 'excel', 'csv'])->default('json');
            $table->string('nombre_archivo', 255)->nullable();
            $table->integer('tamaño_kb')->nullable();

            // Usuario que generó el reporte
            $table->unsignedBigInteger('generado_por')->nullable();
            $table->foreign('generado_por')->references('id')->on('usuarios')->onDelete('set null');

            // Estado del reporte
            $table->enum('estado', ['generando', 'completado', 'error'])->default('generando');
            $table->text('mensaje_error')->nullable();

            // Tiempo de generación
            $table->integer('tiempo_generacion_segundos')->nullable();

            // Estadísticas del reporte
            $table->integer('total_registros')->nullable()->comment('Total de registros procesados');
            $table->integer('total_paginas')->nullable()->comment('Para reportes PDF');

            // Control de acceso
            $table->boolean('es_publico')->default(false);
            $table->timestamp('expira_en')->nullable()->comment('Fecha de expiración del reporte');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipo_reporte');
            $table->index('generado_por');
            $table->index(['fecha_inicio', 'fecha_fin'], 'idx_reporte_fechas');
            $table->index('estado');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_reportes_generados');
    }
};
