<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pre_muestras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mercado_id')->constrained('pre_mercados');
            $table->foreignId('producto_id')->constrained('pre_productos');
            $table->foreignId('encuestador_id')->constrained('pre_encuestadores');
            $table->date('fecha');

            // Datos de la muestra individual
            $table->tinyInteger('muestra_nro')->nullable()->comment('Número de muestra: 1, 2, 3 o 4');
            $table->integer('punto_nro')->nullable()->comment('Número de punto de muestreo');
            $table->tinyInteger('calidad')->nullable()->comment('1=Extra, 2=Primera, 3=Segunda, 4=Tercera, 5=Descarte');
            $table->decimal('precio', 10, 2);
            $table->string('moneda', 3)->default('PEN');

            // Procedencias
            $table->string('procedencia_principal', 150)->nullable()->comment('Procedencia 1: Región/distrito principal');
            $table->string('procedencia_secundaria', 150)->nullable()->comment('Procedencia 2: Región/distrito alternativa');
            $table->text('observaciones')->nullable();

            // Auditoría y validación
            $table->boolean('validado')->default(false);
            $table->foreignId('validado_por')->nullable()->constrained('usuarios');
            $table->dateTime('validado_at')->nullable();
            $table->foreignId('usuario_id')->constrained('usuarios')->comment('Usuario que digitó');

            $table->timestamps();
            $table->softDeletes();

            // Índice único para evitar duplicados
            $table->unique(['mercado_id', 'producto_id', 'fecha', 'muestra_nro']);
        });

        // Agregar constraints CHECK (después de crear la tabla)
        DB::statement('ALTER TABLE pre_muestras ADD CONSTRAINT chk_muestra_nro CHECK (muestra_nro BETWEEN 1 AND 4)');
        DB::statement('ALTER TABLE pre_muestras ADD CONSTRAINT chk_calidad CHECK (calidad BETWEEN 1 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_muestras');
    }
};
