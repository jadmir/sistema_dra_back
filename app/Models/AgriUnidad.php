<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriUnidad extends Model
{
    use HasFactory;

    protected $table = 'agri_unidades';

    protected $fillable = [
        'nombre', 
        'estado',
        'usuario_id'
    ];

    public function variables()
    {
        return $this->hasMany(AgriVariableCatalogo::class, 'unidad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
