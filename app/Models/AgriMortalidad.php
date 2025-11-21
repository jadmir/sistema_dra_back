<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriMortalidad extends Model
{
    use HasFactory;

    protected $table = 'agri_mortalidad';

    protected $fillable = [
        'id_agri_variedad_animal',
        'id_agri_registro_pecuario',
        'cantidad',
    ];

    public function variedad()
    {
        return $this->belongsTo(AgriVariedadAnimal::class, 'id_agri_variedad_animal');
    }

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'id_agri_registro_pecuario');
    }
}
