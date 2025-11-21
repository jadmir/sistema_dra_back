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
        Schema::create('agri_registro_pecuarios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_establo', 200)->nullable();
            $table->string('ubigeo', 100);
            $table->string('mes_de_referencia', 250);
            $table->year('anio');
            $table->string('region', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100);
            $table->string('nombre_establo', 250);
            $table->string('producto_razon_social', 250);
            $table->string('direccion', 100);
            $table->string('ruc', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_registro_pecuarios');
    }
};
