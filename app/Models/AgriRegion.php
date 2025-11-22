<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriRegion extends Model
{
    use HasFactory;

    protected $table = 'agri_regiones';

    protected $fillable = [
        'nombre', 
        'estado', 
        'usuario_id'
    ];

    public function provincias()
    {
        return $this->hasMany(AgriProvincia::class, 'id_region');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
