<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriProductoLeche extends Model
{
    use HasFactory;

    protected $table = 'agri_producto_leches';

    protected $fillable = [
        'registro_pecuario_id',
        'agri_destinos_id',
        'leche_fresca_id',
        'cantidad',
        'precio',
        'usuario_id',
    ];

    public function registroPecuario()
    {
        return $this->belongsTo(AgriRegistroPecuario::class, 'registro_pecuario_id');
    }

    public function destino()
    {
        return $this->belongsTo(AgriDestino::class, 'agri_destinos_id');
    }

    public function totalLeche()
    {
        return $this->belongsTo(LecheFresca::class, 'leche_fresca_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
