<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AgriSupervisor extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_supervisores_insumos';

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
        'region_supervisada',
        'provincias_asignadas',
        'fecha_asignacion',
        'nivel',                 // junior, intermedio, senior (nombre real en BD)
        'ambito_supervision',    // distrital, provincial, regional, nacional
        'estado',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        // Nota: provincias_asignadas usa mutator/accessor manual (ver abajo)
        'fecha_asignacion' => 'date',
    ];

    /**
     * Campos adicionales que se incluyen en el JSON
     */
    protected $appends = [
        'nombre_completo',
        'es_activo',
        'provincias_texto',
        'nivel_supervisor', // Alias para 'nivel'
    ];

    /**
     * Mutator para nivel_supervisor (alias de nivel)
     * Permite usar 'nivel_supervisor' en el API pero se guarda como 'nivel' en BD
     */
    public function setNivelSupervisorAttribute($value)
    {
        $this->attributes['nivel'] = $value;
    }

    /**
     * Accessor para nivel_supervisor (alias de nivel)
     * Devuelve el campo 'nivel' de la BD con el nombre 'nivel_supervisor'
     */
    public function getNivelSupervisorAttribute()
    {
        return $this->attributes['nivel'] ?? null;
    }

    /**
     * Mutator para provincias_asignadas - Convierte array a JSON al guardar
     */
    public function setProvinciasAsignadasAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['provincias_asignadas'] = null;
        } elseif (is_array($value)) {
            $this->attributes['provincias_asignadas'] = json_encode($value);
        } else {
            $this->attributes['provincias_asignadas'] = $value;
        }
    }

    /**
     * Accessor para provincias_asignadas - Convierte JSON a array al leer
     */
    public function getProvinciasAsignadasAttribute($value)
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
     * Relación con Encuestas supervisadas
     */
    public function encuestas(): HasMany
    {
        return $this->hasMany(AgriEncuesta::class, 'supervisor_id');
    }

    /**
     * Relación con Encuestadores a través de Asignaciones
     */
    public function encuestadores(): BelongsToMany
    {
        return $this->belongsToMany(
            AgriEncuestador::class,
            'agri_asignaciones_insumos',
            'supervisor_id',
            'encuestador_id'
        )->withPivot(['tipo_formulario', 'provincia', 'fecha_inicio', 'fecha_fin', 'estado'])
          ->withTimestamps();
    }

    /**
     * Relación con Asignaciones
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AgriAsignacion::class, 'supervisor_id');
    }

    /**
     * Scope para supervisores activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para filtrar por región
     */
    public function scopeRegion($query, $region)
    {
        return $query->where('region_supervisada', $region);
    }

    /**
     * Scope para filtrar por provincia
     */
    public function scopeProvincia($query, $provincia)
    {
        return $query->whereJsonContains('provincias_asignadas', $provincia);
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
     * Accessor para provincias formateadas
     */
    public function getProvinciasTextoAttribute()
    {
        if (empty($this->provincias_asignadas)) {
            return 'Sin provincias asignadas';
        }

        return implode(', ', $this->provincias_asignadas);
    }

    /**
     * Contar encuestas supervisadas
     */
    public function encuestasSupervisadas()
    {
        return $this->encuestas()->count();
    }

    /**
     * Contar encuestas validadas
     */
    public function encuestasValidadas()
    {
        return $this->encuestas()->where('estado', 'validado')->count();
    }

    /**
     * Contar encuestas rechazadas
     */
    public function encuestasRechazadas()
    {
        return $this->encuestas()->where('estado', 'rechazado')->count();
    }

    /**
     * Contar encuestadores asignados
     */
    public function encuestadoresAsignados()
    {
        return $this->asignaciones()->where('estado', 'activa')->distinct('encuestador_id')->count();
    }
}
