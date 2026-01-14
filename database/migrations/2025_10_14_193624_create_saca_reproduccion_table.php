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
        Schema::create('saca_reproduccion', function (Blueprint $table) {
            $table->id();
            $table->string('saca_unidad', 100)->nullable();
            $table->decimal('precio_venta', 18, 2)->nullable();
            $table->foreignId('id_agri_registro_pecuario')->constrained('agri_registro_pecuarios');
            $table->foreignId('id_agri_variedad_animal')->constrained('agri_variedad_animal');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saca_reproduccion');
    }
};
