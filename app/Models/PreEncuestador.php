<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreEncuestador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_encuestadores';

    protected $fillable = [
        'codigo',
        'nombre',
        'dni',
        'telefono',
        'email',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function muestras()
    {
        return $this->hasMany(PreMuestra::class, 'encuestador_id');
    }

    // Scope para encuestadores activos
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} ({$this->codigo})";
    }
}
