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
        Schema::create('agri_saca_clases', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 150);
        $table->text('descripcion')->nullable();
        $table->string('estado', 10)->default('ACTIVO');
        $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_saca_clases');
    }
};
