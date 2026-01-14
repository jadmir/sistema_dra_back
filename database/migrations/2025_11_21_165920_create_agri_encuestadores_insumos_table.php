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
        Schema::create('agri_encuestadores_insumos', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('dni', 8)->unique()->comment('DNI del encuestador');
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F', 'Otro'])->nullable();

            // Contacto
            $table->string('telefono', 15)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('direccion', 255)->nullable();

            // Ubicación asignada
            $table->string('region_asignada', 100)->nullable();
            $table->string('provincia_asignada', 100)->nullable();
            $table->string('distrito_asignado', 100)->nullable();

            // Cargo y especialización
            $table->string('cargo', 50)->default('Encuestador SIEA');
            $table->set('especializacion', ['maquinaria', 'fertilizantes', 'agroquimicos', 'transporte'])
                  ->nullable()
                  ->comment('Tipos de formularios en los que está especializado');

            // Credenciales
            $table->unsignedBigInteger('usuario_id')->unique()->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');

            // Estado laboral
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'inactivo'])->default('activo');
            $table->text('motivo_inactividad')->nullable();

            // Capacitación
            $table->date('fecha_capacitacion')->nullable();
            $table->boolean('certificado_senasa')->default(false);

            // Auditoría
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('usuarios')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('dni');
            $table->index('estado');
            $table->index(['region_asignada', 'provincia_asignada', 'distrito_asignado'], 'idx_encuestador_ubicacion');
            $table->fullText(['nombres', 'apellido_paterno', 'apellido_materno'], 'ft_enc_nombres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_encuestadores_insumos');
    }
};
