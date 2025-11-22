<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriNatalidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_natalidad';

    protected $fillable = [
        'natalidad_mortalidad_id',
        'id_agri_registro_pecuario',
        'cantidad',
    ];

    public function natalidadMortalidad()
    {
        return $this->belongsTo(AgriNatalidadMortalidad::class, 'natalidad_mortalidad_id');
    }

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'id_agri_registro_pecuario');
    }
}
