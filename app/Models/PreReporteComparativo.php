<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreReporteComparativo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_reporte_comparativo';

    protected $fillable = [
        'producto_id',
        'ubicacion_id',
        'fecha',
        'precio_mayorista_promedio',
        'precio_mayorista_minimo',
        'precio_mayorista_maximo',
        'num_mercados_mayoristas',
        'num_muestras_mayoristas',
        'precio_minorista_promedio',
        'precio_minorista_minimo',
        'precio_minorista_maximo',
        'num_mercados_minoristas',
        'num_muestras_minoristas',
        'variacion_porcentual'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_mayorista_promedio' => 'decimal:2',
        'precio_mayorista_minimo' => 'decimal:2',
        'precio_mayorista_maximo' => 'decimal:2',
        'precio_minorista_promedio' => 'decimal:2',
        'precio_minorista_minimo' => 'decimal:2',
        'precio_minorista_maximo' => 'decimal:2',
        'variacion_porcentual' => 'decimal:2',
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(PreProducto::class, 'producto_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(PreGeoUbicacion::class, 'ubicacion_id');
    }

    // Scopes
    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    // Accessor para variación en texto
    public function getVariacionTextoAttribute()
    {
        if ($this->variacion_porcentual > 0) {
            return "+{$this->variacion_porcentual}%";
        }
        return "{$this->variacion_porcentual}%";
    }
}
