<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AgriEncuestador extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_encuestadores_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'email',
        'telefono',
        'especializacion',
        'provincia_asignada',
        'fecha_contratacion',
        'estado',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'fecha_contratacion' => 'date',
        // Nota: especializacion usa mutator/accessor manual (ver abajo)
    ];

    /**
     * Mutator para especializacion - Convierte array a JSON al guardar
     */
    public function setEspecializacionAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['especializacion'] = null;
        } elseif (is_array($value)) {
            $this->attributes['especializacion'] = json_encode($value);
        } else {
            $this->attributes['especializacion'] = $value;
        }
    }

    /**
     * Accessor para especializacion - Convierte JSON a array al leer
     */
    public function getEspecializacionAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Relación con Encuestas
     */
    public function encuestas(): HasMany
    {
        return $this->hasMany(AgriEncuesta::class, 'encuestador_id');
    }

    /**
     * Relación con Supervisores a través de Asignaciones
     */
    public function supervisores(): BelongsToMany
    {
        return $this->belongsToMany(
            AgriSupervisor::class,
            'agri_asignaciones_insumos',
            'encuestador_id',
            'supervisor_id'
        )->withPivot(['tipo_formulario', 'provincia', 'fecha_inicio', 'fecha_fin', 'estado'])
          ->withTimestamps();
    }

    /**
     * Relación con Asignaciones
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AgriAsignacion::class, 'encuestador_id');
    }

    /**
     * Scope para encuestadores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para filtrar por provincia
     */
    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia_asignada', $provincia);
    }

    /**
     * Scope para filtrar por especialización
     */
    public function scopeEspecializacion($query, $especializacion)
    {
        return $query->whereJsonContains('especializacion', $especializacion);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    /**
     * Accessor para verificar si está activo
     */
    public function getEsActivoAttribute()
    {
        return $this->estado === 'activo';
    }

    /**
     * Accessor para especialización formateada
     */
    public function getEspecializacionTextoAttribute()
    {
        if (empty($this->especializacion)) {
            return 'Sin especialización';
        }

        return implode(', ', $this->especializacion);
    }

    /**
     * Contar encuestas completadas
     */
    public function encuestasCompletadas()
    {
        return $this->encuestas()->where('estado', 'validado')->count();
    }

    /**
     * Contar encuestas pendientes
     */
    public function encuestasPendientes()
    {
        return $this->encuestas()->whereIn('estado', ['borrador', 'enviado'])->count();
    }
}
