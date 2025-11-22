<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriRegistroDetalle extends Model
{
    use HasFactory;

    protected $table = 'agri_registro_detalles';

    protected $fillable = [
        'registro_id',
        'cultivo_id',
        'usuario_id'
    ];

    public function registro()
    {
        return $this->belongsTo(AgriRegistro::class, 'registro_id');
    }

    public function cultivo()
    {
        return $this->belongsTo(AgriCultivoCatalogo::class, 'cultivo_id');
    }

    public function variables()
    {
        return $this->hasMany(AgriRegistroVariable::class, 'detalle_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
