<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriCultivoCatalogo extends Model
{
    use HasFactory;

    protected $table = 'agri_cultivo_catalogos';

    protected $fillable = [
        'nombre', 
        'estado',
        'usuario_id'
    ];

    public function detalles()
    {
        return $this->hasMany(AgriRegistroDetalle::class, 'cultivo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
