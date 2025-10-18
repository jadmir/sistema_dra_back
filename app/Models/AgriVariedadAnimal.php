<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriVariedadAnimal extends Model
{
    use HasFactory;

    protected $table = 'agri_variedad_animal';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'usuario_id',
    ];


    public function animales()
    {
        return $this->hasMany(AgriAnimales::class, 'variedad_id');
    }

    public function sacaReproduccion()
    {
        return $this->hasMany(SacaReproduccion::class, 'id_agri_variedad_animal');
    }

    public function sacaVacunoDescarte()
    {
        return $this->hasMany(SacaVacunoDescarte::class, 'id_agri_variedad_animal');
    }

    public function mortalidad()
    {
        return $this->hasMany(AgriMortalidad::class, 'id_agri_variedad_animal');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
