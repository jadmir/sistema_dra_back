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
            $table->foreignId('variedad_id')->nullable()->constrained('agri_variedad_animal')->nullOnDelete();
            $table->integer('edad')->nullable();
            $table->decimal('peso', 10, 2)->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
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
