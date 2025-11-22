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
        Schema::create('agri_provincias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('agri_regiones');
            $table->string('nombre', 150);
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
        Schema::dropIfExists('agri_provincias');
    }
};
