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
        Schema::create('agri_producto_leches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_pecuario_id')->constrained('agri_registro_pecuarios');
            $table->foreignId('agri_destinos_id')->constrained('agri_destinos');
            $table->foreignId('leche_fresca_id')->constrained('leche_fresca');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->string('cantidad', 200)->nullable();
            $table->decimal('precio', 18, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_producto_leches');
    }
};
