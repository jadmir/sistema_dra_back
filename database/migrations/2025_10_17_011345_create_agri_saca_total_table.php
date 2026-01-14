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
        Schema::create('agri_saca_total', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_agri_registro_pecuario')->constrained('agri_registro_pecuarios');
            $table->string('total_leche', 150);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_saca_total');
    }
};
