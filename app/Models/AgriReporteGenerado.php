<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriReporteGenerado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_reportes_generados';

    protected $fillable = [
        'tipo_reporte',
        'parametros',
        'fecha_inicio',
        'fecha_fin',
        'resultado',
        'archivo_path',
        'formato',
        'nombre_archivo',
        'tamaño_kb',
        'generado_por',
        'estado',
        'mensaje_error',
        'tiempo_generacion_segundos',
        'total_registros',
        'total_paginas',
        'es_publico',
        'expira_en'
    ];

    protected $casts = [
        'parametros' => 'array',
        'resultado' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'es_publico' => 'boolean',
        'expira_en' => 'datetime'
    ];

    // Relaciones
    public function generador()
    {
        return $this->belongsTo(Usuario::class, 'generado_por');
    }

    // Scopes
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }

    public function scopeCompletado($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopeVigente($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expira_en')
              ->orWhere('expira_en', '>', now());
        });
    }

    // Accessors
    public function getEsValidoAttribute()
    {
        if (!$this->expira_en) {
            return true;
        }
        return $this->expira_en->isFuture();
    }

    public function getTamañoMbAttribute()
    {
        return $this->tamaño_kb ? round($this->tamaño_kb / 1024, 2) : null;
    }
}
