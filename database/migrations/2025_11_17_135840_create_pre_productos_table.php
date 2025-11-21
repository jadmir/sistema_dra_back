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
        Schema::create('pre_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('pre_categorias');
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 150);
            $table->string('nombre_cientifico', 150)->nullable();
            $table->string('unidad_medida', 50)->default('kg');
            $table->decimal('equivalencia_kg', 8, 4)->nullable()->comment('Equivalencia a kilogramos');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['categoria_id', 'estado']);
        });
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_productos');
    }
};
