<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriVariableCatalogo extends Model
{
    use HasFactory;

    protected $table = 'agri_variable_catalogos';

    protected $fillable = [
        'nombre', 
        'unidad_id', 
        'estado',
        'usuario_id',
    ];

    public function unidad()
    {
        return $this->belongsTo(AgriUnidad::class, 'unidad_id');
    }

    public function registroVariables()
    {
        return $this->hasMany(AgriRegistroVariable::class, 'variable_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
