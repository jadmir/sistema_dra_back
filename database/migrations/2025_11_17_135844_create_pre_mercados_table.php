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
        Schema::create('pre_mercados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
            $table->string('zona', 100)->nullable();
            $table->enum('tipo', ['MAYORISTA', 'MINORISTA'])->default('MINORISTA');
            $table->foreignId('ubicacion_id')->nullable()->constrained('pre_geo_ubicacion');
            $table->decimal('latitud', 10, 8)->nullable()->comment('Coordenada GPS');
            $table->decimal('longitud', 11, 8)->nullable()->comment('Coordenada GPS');
            $table->string('telefono', 20)->nullable();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['tipo', 'estado']);
        });
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_mercados');
    }
};
