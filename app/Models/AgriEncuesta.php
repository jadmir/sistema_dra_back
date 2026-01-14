<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgriEncuesta extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_encuestas_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'tipo_formulario',
        'region',
        'provincia',
        'distrito',
        'localidad',
        'anio',
        'mes',
        'fecha_recoleccion',
        'encuestador_id',
        'supervisor_id',
        'nombre_informante',
        'telefono_informante',
        'fuente_informacion',
        'estado',
        'fecha_envio',
        'fecha_validacion',
        'observaciones_supervisor',
        'firma_supervisor',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'fecha_recoleccion' => 'date',
        'fecha_envio' => 'datetime',
        'fecha_validacion' => 'datetime',
        'anio' => 'integer',
        'mes' => 'integer',
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
     * Scope para filtrar por tipo de formulario
     */
    public function scopeTipoFormulario($query, $tipo)
    {
        return $query->where('tipo_formulario', $tipo);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
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
     * Scope para filtrar por período
     */
    public function scopePeriodo($query, $anio, $mes = null)
    {
        $query->where('anio', $anio);

        if ($mes !== null) {
            $query->where('mes', $mes);
        }

        return $query;
    }

    /**
     * Scope para filtrar por provincia
     */
    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia', $provincia);
    }

    /**
     * Scope para encuestas validadas
     */
    public function scopeValidadas($query)
    {
        return $query->where('estado', 'validado');
    }

    /**
     * Scope para encuestas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->whereIn('estado', ['borrador', 'enviado']);
    }

    /**
     * Accessor para nombre completo del período
     */
    public function getPeriodoTextoAttribute()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$this->mes] . ' ' . $this->anio;
    }

    /**
     * Accessor para verificar si está validada
     */
    public function getEsValidadaAttribute()
    {
        return $this->estado === 'validado';
    }

    /**
     * Accessor para color de estado
     */
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'validado' => 'success',
            'enviado' => 'info',
            'rechazado' => 'danger',
            'borrador' => 'warning',
            default => 'secondary'
        };
    }
}
