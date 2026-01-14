<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgriPrecioTransporte extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_precios_transporte_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'encuesta_id',
        'ruta',
        'origen',
        'destino',
        'tipo_vehiculo',
        'capacidad_carga',
        'precio_viaje',
        'distancia_km',
        'costo_por_km',
        'costo_por_tonelada',
        'fecha_registro',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'capacidad_carga' => 'decimal:2',
        'precio_viaje' => 'decimal:2',
        'distancia_km' => 'decimal:2',
        'costo_por_km' => 'decimal:2',
        'costo_por_tonelada' => 'decimal:2',
        'fecha_registro' => 'date',
    ];

    /**
     * Relación con Encuesta
     */
    public function encuesta(): BelongsTo
    {
        return $this->belongsTo(AgriEncuesta::class, 'encuesta_id');
    }

    /**
     * Scope para filtrar por tipo de vehículo
     */
    public function scopeTipoVehiculo($query, $tipo)
    {
        return $query->where('tipo_vehiculo', $tipo);
    }

    /**
     * Scope para filtrar por origen
     */
    public function scopeOrigen($query, $origen)
    {
        return $query->where('origen', $origen);
    }

    /**
     * Scope para filtrar por destino
     */
    public function scopeDestino($query, $destino)
    {
        return $query->where('destino', $destino);
    }

    /**
     * Scope para filtrar por ruta
     */
    public function scopeRuta($query, $ruta)
    {
        return $query->where('ruta', $ruta);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopePeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_registro', [$fechaInicio, $fechaFin]);
    }

    /**
     * Accessor para ruta formateada
     */
    public function getRutaTextoAttribute()
    {
        return "{$this->origen} → {$this->destino}";
    }

    /**
     * Accessor para información de vehículo
     */
    public function getVehiculoTextoAttribute()
    {
        return "{$this->tipo_vehiculo} ({$this->capacidad_carga} TN)";
    }

    /**
     * Accessor para precio formateado
     */
    public function getPrecioFormateadoAttribute()
    {
        return 'S/ ' . number_format($this->precio_viaje, 2);
    }

    /**
     * Accessor para costo por km formateado
     */
    public function getCostoPorKmFormateadoAttribute()
    {
        if (!$this->costo_por_km) {
            return 'N/A';
        }

        return 'S/ ' . number_format($this->costo_por_km, 2) . '/km';
    }

    /**
     * Accessor para costo por tonelada formateado
     */
    public function getCostoPorToneladaFormateadoAttribute()
    {
        if (!$this->costo_por_tonelada) {
            return 'N/A';
        }

        return 'S/ ' . number_format($this->costo_por_tonelada, 2) . '/TN';
    }

    /**
     * Método para calcular costo por kilómetro
     */
    public function calcularCostoPorKm()
    {
        if ($this->distancia_km && $this->distancia_km > 0) {
            $this->costo_por_km = $this->precio_viaje / $this->distancia_km;
            return $this->costo_por_km;
        }

        return null;
    }

    /**
     * Método para calcular costo por tonelada
     */
    public function calcularCostoPorTonelada()
    {
        if ($this->capacidad_carga && $this->capacidad_carga > 0) {
            $this->costo_por_tonelada = $this->precio_viaje / $this->capacidad_carga;
            return $this->costo_por_tonelada;
        }

        return null;
    }

    /**
     * Boot del modelo para calcular automáticamente costos
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($precio) {
            $precio->calcularCostoPorKm();
            $precio->calcularCostoPorTonelada();
        });

        static::updating(function ($precio) {
            $precio->calcularCostoPorKm();
            $precio->calcularCostoPorTonelada();
        });
    }
}
