<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SacaVacunoDescarte extends Model
{
    use HasFactory;

    protected $table = 'saca_vacuno_descarte';

    protected $fillable = [
        'saca_unidad',
        'precio_venta',
        'peso_promedio_vivo',
        'id_agri_registro_pecuario',
        'id_agri_variedad_animal',
        'usuario_id',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'id_agri_registro_pecuario');
    }

    public function variedad()
    {
        return $this->belongsTo(AgriVariedadAnimal::class, 'id_agri_variedad_animal');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
