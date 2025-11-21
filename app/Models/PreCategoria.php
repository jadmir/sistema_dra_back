<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreCategoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_categorias';

    protected $fillable = [
        'nombre',
        'codigo',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(PreProducto::class, 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scope para categorías activas
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }
}
