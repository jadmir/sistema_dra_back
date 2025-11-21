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
        Schema::create('agri_variedads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->nullable()->constrained('agri_productos')->nullOnDelete()->onUpdate('cascade');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true); 
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete()->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_variedads');
    }
};
