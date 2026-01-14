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
        Schema::create('agri_metas_mensuales', function (Blueprint $table) {
            $table->id();

            // Período de la meta
            $table->integer('año');
            $table->integer('mes');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            // A quién aplica la meta
            $table->enum('aplica_a', ['encuestador', 'supervisor', 'provincia', 'regional'])->default('encuestador');
            $table->unsignedBigInteger('encuestador_id')->nullable();
            $table->foreign('encuestador_id')->references('id')->on('agri_encuestadores_insumos')->onDelete('cascade');
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->foreign('supervisor_id')->references('id')->on('agri_supervisores_insumos')->onDelete('cascade');
            $table->string('provincia', 100)->nullable();

            // Metas por tipo de formulario
            $table->integer('meta_f1')->default(0)->comment('Meta de encuestas F-1 (Maquinaria)');
            $table->integer('meta_f4')->default(0)->comment('Meta de encuestas F-4 (Fertilizantes)');
            $table->integer('meta_f6')->default(0)->comment('Meta de encuestas F-6 (Agroquímicos)');
            $table->integer('meta_f14')->default(0)->comment('Meta de encuestas F-14 (Transporte)');
            $table->integer('meta_total')->default(0)->comment('Suma de todas las metas');

            // Resultados alcanzados (se actualiza automáticamente)
            $table->integer('logrado_f1')->default(0);
            $table->integer('logrado_f4')->default(0);
            $table->integer('logrado_f6')->default(0);
            $table->integer('logrado_f14')->default(0);
            $table->integer('logrado_total')->default(0);

            // Porcentajes de cumplimiento
            $table->decimal('porcentaje_f1', 5, 2)->default(0);
            $table->decimal('porcentaje_f4', 5, 2)->default(0);
            $table->decimal('porcentaje_f6', 5, 2)->default(0);
            $table->decimal('porcentaje_f14', 5, 2)->default(0);
            $table->decimal('porcentaje_total', 5, 2)->default(0);

            // Estado de cumplimiento
            $table->enum('estado', ['pendiente', 'en_progreso', 'cumplido', 'superado', 'no_cumplido'])->default('pendiente');
            $table->text('observaciones')->nullable();

            // Quien estableció la meta
            $table->unsignedBigInteger('establecido_por')->nullable();
            $table->foreign('establecido_por')->references('id')->on('usuarios')->onDelete('set null');

            // Última actualización de logros
            $table->timestamp('ultima_actualizacion_logros')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['año', 'mes'], 'idx_periodo');
            $table->index('encuestador_id');
            $table->index('supervisor_id');
            $table->index('provincia');
            $table->index('estado');
            $table->index('aplica_a');

            // Índice único para evitar duplicados
            $table->unique(['año', 'mes', 'aplica_a', 'encuestador_id', 'supervisor_id', 'provincia'], 'unique_meta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_metas_mensuales');
    }
};
