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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('dni',20)->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('direccion', 150)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('password');
            $table->foreignId('rol_id')->constrained('roles');
            $table->boolean('activo')->default(true); //borrado logico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
