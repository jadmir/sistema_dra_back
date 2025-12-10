<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriRegistro extends Model
{
    use HasFactory;

    protected $table = 'agri_registros';

    protected $fillable = [
        'region_id',
        'provincia_id',
        'distrito_id',
        'anio',
        'observacion',
        'estado',
        'usuario_id',
    ];

    public function region()
    {
        return $this->belongsTo(AgriRegion::class, 'region_id');
    }

    public function provincia()
    {
        return $this->belongsTo(AgriProvincia::class, 'provincia_id');
    }

    public function distrito()
    {
        return $this->belongsTo(AgriDistrito::class, 'distrito_id');
    }

    public function detalles()
    {
        return $this->hasMany(AgriRegistroDetalle::class, 'registro_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class, 'cultivo_id');
    }

    public function variables()
    {
        return $this->hasMany(AgriRegistroVariable::class, 'detalle_id');
    }
}
