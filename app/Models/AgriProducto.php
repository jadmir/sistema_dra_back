<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriProducto extends Model
{
     use HasFactory;

    protected $table = 'agri_productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'usuario_id'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Usuario que creó el producto
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Variedades relacionadas
    public function variedades()
    {
        return $this->hasMany(AgriVariedad::class, 'producto_id');
    }
}
