<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgriAsignacion extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_asignaciones_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'encuestador_id',
        'supervisor_id',
        'tipo_formulario',
        'provincia',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Relación con Encuestador
     */
    public function encuestador(): BelongsTo
    {
        return $this->belongsTo(AgriEncuestador::class, 'encuestador_id');
    }

    /**
     * Relación con Supervisor
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(AgriSupervisor::class, 'supervisor_id');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope para asignaciones finalizadas
     */
    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'finalizada');
    }

    /**
     * Scope para filtrar por encuestador
     */
    public function scopeEncuestador($query, $encuestadorId)
    {
        return $query->where('encuestador_id', $encuestadorId);
    }

    /**
     * Scope para filtrar por supervisor
     */
    public function scopeSupervisor($query, $supervisorId)
    {
        return $query->where('supervisor_id', $supervisorId);
    }

    /**
     * Scope para filtrar por tipo de formulario
     */
    public function scopeTipoFormulario($query, $tipo)
    {
        return $query->where('tipo_formulario', $tipo);
    }

    /**
     * Scope para filtrar por provincia
     */
    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia', $provincia);
    }

    /**
     * Scope para asignaciones vigentes (activas y en período)
     */
    public function scopeVigentes($query)
    {
        $hoy = now();
        return $query->where('estado', 'activa')
                    ->where('fecha_inicio', '<=', $hoy)
                    ->where(function($q) use ($hoy) {
                        $q->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', $hoy);
                    });
    }

    /**
     * Accessor para verificar si está activa
     */
    public function getEsActivaAttribute()
    {
        return $this->estado === 'activa';
    }

    /**
     * Accessor para verificar si está vigente
     */
    public function getEsVigenteAttribute()
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        $hoy = now();

        if ($this->fecha_inicio > $hoy) {
            return false;
        }

        if ($this->fecha_fin && $this->fecha_fin < $hoy) {
            return false;
        }

        return true;
    }

    /**
     * Accessor para período formateado
     */
    public function getPeriodoTextoAttribute()
    {
        $inicio = $this->fecha_inicio->format('d/m/Y');
        $fin = $this->fecha_fin ? $this->fecha_fin->format('d/m/Y') : 'Indefinido';

        return "{$inicio} - {$fin}";
    }
}
