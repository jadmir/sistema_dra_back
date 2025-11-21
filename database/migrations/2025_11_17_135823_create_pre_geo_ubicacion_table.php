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
        Schema::create('pre_geo_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->string('departamento', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['departamento', 'provincia']);
        });
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_geo_ubicacion');
    }
};
