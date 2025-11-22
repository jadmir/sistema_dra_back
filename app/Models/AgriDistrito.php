<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriDistrito extends Model
{
    use HasFactory;

    protected $table = 'agri_distritos';

    protected $fillable = [
        'provincia_id', 
        'nombre', 
        'estado',
        'usuario_id'
    ];

    public function provincia()
    {
        return $this->belongsTo(AgriProvincia::class, 'provincia_id');
    }

    public function registros()
    {
        return $this->hasMany(AgriRegistro::class, 'id_distrito');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
