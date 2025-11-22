<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriRegistroVariable extends Model
{
    use HasFactory;

    protected $table = 'agri_registro_variables';

    protected $fillable = [
        'detalle_id',
        'variable_id',
        'ene',
        'feb',
        'mar',
        'abr',
        'may',
        'jun',
        'jul',
        'ago',
        'sep',
        'oct',
        'nov',
        'dic',
        'total_anual',
        'usuario_id',
    ];

    public function detalle()
    {
        return $this->belongsTo(AgriRegistroDetalle::class, 'detalle_id');
    }

    public function variableCatalogo()
    {
        return $this->belongsTo(AgriVariableCatalogo::class, 'variable_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
