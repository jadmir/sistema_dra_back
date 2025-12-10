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
        Schema::create('agri_registro_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_id')->constrained('agri_registro_detalles');
            $table->foreignId('variable_id')->constrained('agri_variable_catalogos');

            foreach (['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'] as $mes) {
                $table->decimal($mes, 10, 2)->default(0);
            }

            $table->decimal('total_anual', 10, 2)->default(0);
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_registro_variables');
    }
};
