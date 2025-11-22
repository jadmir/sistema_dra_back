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
        $tablas = [
            'agri_animales',
            'agri_natalidad',
            'agri_mortalidad',
            'agri_producto_leches',
            'leche_fresca',
            'saca_reproduccion',
            'saca_vacuno_descarte'
        ];

        foreach ($tablas as $tabla) {
            if (!Schema::hasColumn($tabla, 'deleted_at')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablas = [
            'agri_animales',
            'agri_natalidad',
            'agri_mortalidad',
            'agri_producto_leches',
            'leche_fresca',
            'saca_reproduccion',
            'saca_vacuno_descarte'
        ];

        foreach ($tablas as $tabla) {
            if (Schema::hasColumn($tabla, 'deleted_at')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
