<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecheFresca extends Model
{
    use HasFactory;

    protected $table = 'leche_fresca';

    protected $fillable = [
        'total_leche',
        'registro_pecuario_id',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'registro_pecuario_id');
    }
}
