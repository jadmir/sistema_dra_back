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
        Schema::create('leche_fresca', function (Blueprint $table) {
            $table->id();
            $table->string('total_leche', 150);
            $table->foreignId('registro_pecuario_id')->constrained('agri_registro_pecuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leche_fresca');
    }
};
