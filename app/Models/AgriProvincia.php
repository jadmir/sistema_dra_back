<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriProvincia extends Model
{
    use HasFactory;
    
    protected $table = 'agri_provincias';

    protected $fillable = [
        'region_id', 
        'nombre', 
        'estado',
        'usuario_id'
    ];

    public function region()
    {
        return $this->belongsTo(AgriRegion::class, 'region_id');
    }

    public function distritos()
    {
        return $this->hasMany(AgriDistrito::class, 'provincia_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
