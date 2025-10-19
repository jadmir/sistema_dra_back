<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriDestino extends Model
{
    use HasFactory;

    protected $table = 'agri_destinos';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
        'activo',
        'usuario_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function productosLeche()
    {
        return $this->hasMany(AgriProductoLeche::class, 'agri_destinos_id');
    }
    
    //relacion con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
