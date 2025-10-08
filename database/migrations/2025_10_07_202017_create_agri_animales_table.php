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
            $table->string('codigo', 50);
            $table->foreignId('variedad_id')->constrained('agri_variedads')->onDelete('cascade');
            $table->integer('edad');
            $table->decimal('peso', 10, 2);
            $table->string('estado', 10)->default('activo');
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
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
