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
        Schema::create('agri_natalidad', function (Blueprint $table) {
            $table->id();
            $table->string('cantidad', 100)->nullable();
            $table->foreignId('natalidad_mortalidad_id')->constrained('agri_natalidad_mortalidad');
            $table->foreignId('id_agri_registro_pecuario')->constrained('agri_registro_pecuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_natalidad');
    }
};
