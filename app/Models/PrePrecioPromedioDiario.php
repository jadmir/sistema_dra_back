<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrePrecioPromedioDiario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_precio_promedio_diario';

    protected $fillable = [
        'mercado_id',
        'producto_id',
        'fecha',
        'tipo_mercado',
        'precio_promedio',
        'precio_minimo',
        'precio_maximo',
        'num_muestras'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_promedio' => 'decimal:2',
        'precio_minimo' => 'decimal:2',
        'precio_maximo' => 'decimal:2',
    ];

    // Relaciones
    public function mercado()
    {
        return $this->belongsTo(PreMercado::class, 'mercado_id');
    }

    public function producto()
    {
        return $this->belongsTo(PreProducto::class, 'producto_id');
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

    public function scopeMayorista($query)
    {
        return $query->where('tipo_mercado', 'MAYORISTA');
    }

    public function scopeMinorista($query)
    {
        return $query->where('tipo_mercado', 'MINORISTA');
    }
}
