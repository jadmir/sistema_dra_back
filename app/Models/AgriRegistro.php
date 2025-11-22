<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriRegistro extends Model
{
     use HasFactory;

    protected $table = 'agri_registros';

    protected $fillable = [
        'distrito_id',
        'anio',
        'observacion',
        'estado',
        'usuario_id',
    ];

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
}
