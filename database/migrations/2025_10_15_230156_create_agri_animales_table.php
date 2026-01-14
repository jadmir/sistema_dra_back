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
        Schema::create('agri_animales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_pecuario_id')->constrained('agri_registro_pecuarios');
            $table->foreignId('variedad_id')->constrained('agri_variedad_animal');
            $table->string('total', 100);
            $table->boolean('estado')->default(true);
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_animales');
    }
};
