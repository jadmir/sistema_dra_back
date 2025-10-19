<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriNatalidadMortalidad extends Model
{
    use HasFactory;

    protected $table = 'agri_natalidad_mortalidad';

    protected $fillable = [
        'concepto',
        'observaciones',
        'usuario_id',
        'estado',
    ];

    public function natalidades()
    {
        return $this->hasMany(AgriNatalidad::class, 'agri_natalidad_mortalidad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
