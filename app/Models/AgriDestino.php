<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriDestino extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_destinos';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
        'activo',
        'usuario_id'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    //relacion con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
