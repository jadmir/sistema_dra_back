<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriAnimales extends Model
{
     use HasFactory;

    protected $table = 'agri_animales';

    protected $fillable = [
        'registro_pecuario_id',
        'variedad_id',
        'total',
        'estado',
        'usuario_id',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'registro_pecuario_id');
    }

    public function variedad()
    {
        return $this->belongsTo(AgriVariedadAnimal::class, 'variedad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

}
