<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreMuestra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pre_muestras';

    protected $fillable = [
        'mercado_id',
        'producto_id',
        'encuestador_id',
        'fecha',
        'muestra_nro',
        'punto_nro',
        'calidad',
        'precio',
        'moneda',
        'procedencia_principal',
        'procedencia_secundaria',
        'observaciones',
        'validado',
        'validado_por',
        'validado_at',
        'usuario_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio' => 'decimal:2',
        'validado' => 'boolean',
        'validado_at' => 'datetime',
    ];

    protected $appends = ['calidad_texto'];

    // Relaciones
    public function mercado()
    {
        return $this->belongsTo(PreMercado::class, 'mercado_id');
    }

    public function producto()
    {
        return $this->belongsTo(PreProducto::class, 'producto_id');
    }

    public function encuestador()
    {
        return $this->belongsTo(PreEncuestador::class, 'encuestador_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function validadorUsuario()
    {
        return $this->belongsTo(Usuario::class, 'validado_por');
    }

    // Scopes
    public function scopeValidado($query)
    {
        return $query->where('validado', true);
    }

    public function scopePendiente($query)
    {
        return $query->where('validado', false);
    }

    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    // Accessor para calidad en texto
    public function getCalidadTextoAttribute()
    {
        return match($this->calidad) {
            1 => 'Extra',
            2 => 'Primera',
            3 => 'Segunda',
            4 => 'Tercera',
            5 => 'Descarte',
            default => 'No especificada'
        };
    }
}
