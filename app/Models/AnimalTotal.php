<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalTotal extends Model
{
    use HasFactory;

    protected $table = 'animal_total';

    protected $fillable = [
        'registro_pecuario_id',
        'total_animal',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'registro_pecuario_id');
    }
}
