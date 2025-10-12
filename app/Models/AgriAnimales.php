<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriAnimales extends Model
{
     use HasFactory;

    protected $table = 'agri_animales';

    protected $fillable = [
        'codigo',
        'variedad_id',
        'edad',
        'peso',
        'estado',
        'usuario_id',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'peso' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function variedad()
    {
        return $this->belongsTo(AgriVariedadAnimal::class, 'variedad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
