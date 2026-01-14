<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformeTecnico extends Model
{
    use HasFactory;

    protected $table = 'informe_tecnico';

    protected $fillable = [
        'id_agri_registro_pecuario',
        'informante',
        'email',
        'telefono',
        'cargo',
        'tecnico',
        'observaciones',
        'fecha',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'id_agri_registro_pecuario');
    }
}
