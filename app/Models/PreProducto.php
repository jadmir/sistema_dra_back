<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'codigo',
        'unidad_medida',
        'equivalencia_kg',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'equivalencia_kg' => 'decimal:4',
        'estado' => 'boolean',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(PreCategoria::class, 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function muestras()
    {
        return $this->hasMany(PreMuestra::class, 'producto_id');
    }

    public function preciosPromedioDiario()
    {
        return $this->hasMany(PrePrecioPromedioDiario::class, 'producto_id');
    }

    public function reportesComparativos()
    {
        return $this->hasMany(PreReporteComparativo::class, 'producto_id');
    }

    // Scope para productos activos
    public function scopeActivo($query)
    {
        return $query->where('estado', true);
    }

    // Accessor para nombre con unidad
    public function getNombreConUnidadAttribute()
    {
        return "{$this->nombre} ({$this->unidad_medida})";
    }
}
