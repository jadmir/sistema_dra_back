<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreMercado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_mercados';

    protected $fillable = [
        'nombre',
        'direccion',
        'zona',
        'tipo',
        'ubicacion_id',
        'latitud',
        'longitud',
        'telefono',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'estado' => 'boolean',
    ];

    // Relaciones
    public function ubicacion()
    {
        return $this->belongsTo(PreGeoUbicacion::class, 'ubicacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function muestras()
    {
        return $this->hasMany(PreMuestra::class, 'mercado_id');
    }

    public function preciosPromedioDiario()
    {
        return $this->hasMany(PrePrecioPromedioDiario::class, 'mercado_id');
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    public function scopeMayorista($query)
    {
        return $query->where('tipo', 'MAYORISTA');
    }

    public function scopeMinorista($query)
    {
        return $query->where('tipo', 'MINORISTA');
    }

    // Accessor para nombre completo con tipo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} ({$this->tipo})";
    }
}
