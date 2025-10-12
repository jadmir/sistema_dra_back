<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriVariedad extends Model
{
    use HasFactory;

    protected $table = 'agri_variedads';

    protected $fillable = [
        'producto_id',
        'usuario_id',
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     public function producto()
    {
        return $this->belongsTo(AgriProducto::class, 'producto_id');
    }

        public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

}
