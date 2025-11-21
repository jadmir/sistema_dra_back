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
        Schema::create('pre_precio_promedio_diario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mercado_id')->nullable()->constrained('pre_mercados');
            $table->foreignId('producto_id')->nullable()->constrained('pre_productos');
            $table->date('fecha');
            $table->enum('tipo_mercado', ['MAYORISTA', 'MINORISTA'])->comment('Copiado de pre_mercados.tipo');
            $table->decimal('precio_promedio', 10, 2)->nullable();
            $table->decimal('precio_minimo', 10, 2)->nullable();
            $table->decimal('precio_maximo', 10, 2)->nullable();
            $table->integer('num_muestras')->nullable()->comment('Cantidad de muestras consideradas');
            $table->timestamps();
            $table->softDeletes();

            // Índice único para evitar duplicados
            $table->unique(['producto_id', 'tipo_mercado', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_precio_promedio_diario');
    }
};
