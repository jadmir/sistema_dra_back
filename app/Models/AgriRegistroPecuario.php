<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriRegistroPecuario extends Model
{
    use HasFactory;

    protected $table = 'agri_registro_pecuarios';

protected $fillable = [
        'codigo_establo',
        'ubigeo',
        'mes_de_referencia',
        'anio',
        'region',
        'provincia',
        'distrito',
        'nombre_establo',
        'producto_razon_social',
        'direccion',
        'ruc',
    ];

    
    public function animales()
    {
        return $this->hasMany(AgriAnimales::class, 'registro_pecuario_id');
    }

    public function productosLeche()
    {
        return $this->hasMany(AgriProductoLeche::class, 'registro_pecuario_id');
    }

    public function sacaReproduccion()
    {
        return $this->hasMany(SacaReproduccion::class, 'id_agri_registro_pecuario');
    }

    public function sacaVacunoDescarte()
    {
        return $this->hasMany(SacaVacunoDescarte::class, 'id_agri_registro_pecuario');
    }

    public function informeTecnico()
    {
        return $this->hasOne(InformeTecnico::class, 'id_agri_registro_pecuario');
    }

    public function animalTotal()
    {
        return $this->hasOne(AnimalTotal::class, 'registro_pecuario_id');
    }

    public function lechefresca()
    {
        return $this->hasOne(LecheFresca::class, 'registro_pecuario_id');
    }

    public function sacaTotal()
    {
        return $this->hasOne(AgriSacaTotal::class, 'id_agri_registro_pecuario');
    }

    public function natalidad()
    {
        return $this->hasMany(AgriNatalidad::class, 'id_agri_registro_pecuario');
    }

    public function mortalidad()
    {
        return $this->hasMany(AgriMortalidad::class, 'id_agri_registro_pecuario');
    }

}
