<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreGeoUbicacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_geo_ubicacion';

    protected $fillable = [
        'departamento',
        'provincia',
        'distrito',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relaciones
    public function mercados()
    {
        return $this->hasMany(PreMercado::class, 'ubicacion_id');
    }

    public function reportesComparativos()
    {
        return $this->hasMany(PreReporteComparativo::class, 'ubicacion_id');
    }

    // Scope para ubicaciones activas
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        $parts = array_filter([$this->distrito, $this->provincia, $this->departamento]);
        return implode(', ', $parts);
    }
}
