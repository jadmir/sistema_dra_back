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
        Schema::create('agri_registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distrito_id')->constrained('agri_distritos');
            $table->year('anio');
            $table->text('observacion')->nullable();
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
        Schema::dropIfExists('agri_registros');
    }
};
