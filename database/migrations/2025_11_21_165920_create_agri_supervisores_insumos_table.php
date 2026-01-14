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
        Schema::create('agri_supervisores_insumos', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('dni', 8)->unique()->comment('DNI del supervisor');
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
            $table->enum('ambito_supervision', ['distrital', 'provincial', 'regional', 'nacional'])->default('provincial');

            // Cargo y nivel
            $table->string('cargo', 50)->default('Supervisor SIEA');
            $table->enum('nivel', ['junior', 'senior', 'jefe'])->default('junior');
            $table->set('especializacion', ['maquinaria', 'fertilizantes', 'agroquimicos', 'transporte'])
                  ->nullable()
                  ->comment('Tipos de formularios que puede supervisar');

            // Credenciales
            $table->unsignedBigInteger('usuario_id')->unique()->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');

            // Firma digital
            $table->string('firma_path', 255)->nullable()->comment('Ruta a imagen de firma');
            $table->boolean('firma_activa')->default(true);

            // Estado laboral
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'inactivo'])->default('activo');
            $table->text('motivo_inactividad')->nullable();

            // Capacitación y certificaciones
            $table->date('fecha_capacitacion')->nullable();
            $table->json('certificaciones')->nullable()->comment('Array de certificaciones');

            // Auditoría
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('usuarios')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('dni');
            $table->index('estado');
            $table->index('nivel');
            $table->index(['region_asignada', 'provincia_asignada'], 'idx_supervisor_ubicacion');
            $table->index('ambito_supervision');
            $table->fullText(['nombres', 'apellido_paterno', 'apellido_materno'], 'ft_sup_nombres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agri_supervisores_insumos');
    }
};
