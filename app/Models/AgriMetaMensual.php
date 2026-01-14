<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriMetaMensual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_metas_mensuales';

    protected $fillable = [
        'año',
        'mes',
        'fecha_inicio',
        'fecha_fin',
        'aplica_a',
        'encuestador_id',
        'supervisor_id',
        'provincia',
        'meta_f1',
        'meta_f4',
        'meta_f6',
        'meta_f14',
        'meta_total',
        'logrado_f1',
        'logrado_f4',
        'logrado_f6',
        'logrado_f14',
        'logrado_total',
        'porcentaje_f1',
        'porcentaje_f4',
        'porcentaje_f6',
        'porcentaje_f14',
        'porcentaje_total',
        'estado',
        'observaciones',
        'establecido_por',
        'ultima_actualizacion_logros'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'porcentaje_f1' => 'decimal:2',
        'porcentaje_f4' => 'decimal:2',
        'porcentaje_f6' => 'decimal:2',
        'porcentaje_f14' => 'decimal:2',
        'porcentaje_total' => 'decimal:2',
        'ultima_actualizacion_logros' => 'datetime'
    ];

    // Relaciones
    public function encuestador()
    {
        return $this->belongsTo(AgriEncuestador::class, 'encuestador_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(AgriSupervisor::class, 'supervisor_id');
    }

    public function establecidoPor()
    {
        return $this->belongsTo(Usuario::class, 'establecido_por');
    }

    // Scopes
    public function scopePeriodo($query, $año, $mes)
    {
        return $query->where('año', $año)->where('mes', $mes);
    }

    public function scopeEncuestador($query, $encuestadorId)
    {
        return $query->where('encuestador_id', $encuestadorId);
    }

    public function scopeSupervisor($query, $supervisorId)
    {
        return $query->where('supervisor_id', $supervisorId);
    }

    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia', $provincia);
    }

    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Métodos de cálculo
    public function actualizarLogros()
    {
        // Este método se llamará automáticamente cuando se validen encuestas
        // Para actualizar los logros en tiempo real

        $this->porcentaje_f1 = $this->meta_f1 > 0 ? ($this->logrado_f1 / $this->meta_f1) * 100 : 0;
        $this->porcentaje_f4 = $this->meta_f4 > 0 ? ($this->logrado_f4 / $this->meta_f4) * 100 : 0;
        $this->porcentaje_f6 = $this->meta_f6 > 0 ? ($this->logrado_f6 / $this->meta_f6) * 100 : 0;
        $this->porcentaje_f14 = $this->meta_f14 > 0 ? ($this->logrado_f14 / $this->meta_f14) * 100 : 0;
        $this->porcentaje_total = $this->meta_total > 0 ? ($this->logrado_total / $this->meta_total) * 100 : 0;

        // Actualizar estado
        if ($this->porcentaje_total >= 100) {
            $this->estado = $this->porcentaje_total > 110 ? 'superado' : 'cumplido';
        } elseif ($this->porcentaje_total > 0) {
            $this->estado = 'en_progreso';
        } elseif ($this->fecha_fin->isPast()) {
            $this->estado = 'no_cumplido';
        }

        $this->ultima_actualizacion_logros = now();
        $this->save();
    }

    // Accessors
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'cumplido', 'superado' => 'success',
            'en_progreso' => 'warning',
            'no_cumplido' => 'danger',
            default => 'secondary'
        };
    }

    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'cumplido' => 'Meta Cumplida',
            'superado' => 'Meta Superada',
            'en_progreso' => 'En Progreso',
            'no_cumplido' => 'No Cumplido',
            default => 'Pendiente'
        };
    }

    public function getPeriodoTextoAttribute()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$this->mes] . ' ' . $this->año;
    }
}
