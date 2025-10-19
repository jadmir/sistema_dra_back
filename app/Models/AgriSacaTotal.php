<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriSacaTotal extends Model
{
    use HasFactory;

    protected $table = 'agri_saca_total';

    protected $fillable = [
        'id_agri_registro_pecuario',
        'total_leche'
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'id_agri_registro_pecuario');
    }
}
