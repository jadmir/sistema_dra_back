<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriAgroquimico extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_agroquimicos_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre_comercial',
        'ingrediente_activo',
        'tipo',
        'categoria_toxicologica',
        'concentracion',
        'formulacion',
        'unidad_medida',
        'registro_senasa',
        'cultivos_objetivo',
        'plagas_objetivo',
        'activo',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'cultivos_objetivo' => 'array',
        'plagas_objetivo' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Scope para agroquímicos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por categoría toxicológica
     */
    public function scopeCategoriaToxicologica($query, $categoria)
    {
        return $query->where('categoria_toxicologica', $categoria);
    }

    /**
     * Scope para buscar por ingrediente activo
     */
    public function scopeIngredienteActivo($query, $ingrediente)
    {
        return $query->where('ingrediente_activo', 'like', "%{$ingrediente}%");
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('nombre_comercial', 'like', "%{$termino}%")
              ->orWhere('ingrediente_activo', 'like', "%{$termino}%");
        });
    }

    /**
     * Scope para filtrar por cultivo objetivo
     */
    public function scopeCultivoObjetivo($query, $cultivo)
    {
        return $query->whereJsonContains('cultivos_objetivo', $cultivo);
    }

    /**
     * Scope para filtrar por plaga objetivo
     */
    public function scopePlagaObjetivo($query, $plaga)
    {
        return $query->whereJsonContains('plagas_objetivo', $plaga);
    }

    /**
     * Accessor para nombre completo con ingrediente activo
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre_comercial;

        if ($this->ingrediente_activo) {
            $nombre .= " ({$this->ingrediente_activo}";

            if ($this->concentracion) {
                $nombre .= " {$this->concentracion}";
            }

            $nombre .= ")";
        }

        if ($this->formulacion) {
            $nombre .= " - {$this->formulacion}";
        }

        return $nombre;
    }

    /**
     * Accessor para verificar si está activo
     */
    public function getEsActivoAttribute()
    {
        return $this->activo === true;
    }

    /**
     * Accessor para verificar si tiene registro SENASA
     */
    public function getTieneRegistroSenasaAttribute()
    {
        return !empty($this->registro_senasa);
    }

    /**
     * Accessor para tipo formateado
     */
    public function getTipoTextoAttribute()
    {
        return match($this->tipo) {
            'herbicida' => 'Herbicida',
            'insecticida' => 'Insecticida',
            'fungicida' => 'Fungicida',
            'acaricida' => 'Acaricida',
            'nematicida' => 'Nematicida',
            'rodenticida' => 'Rodenticida',
            'otros' => 'Otros',
            default => ucfirst($this->tipo)
        };
    }

    /**
     * Accessor para categoría toxicológica con color
     */
    public function getCategoriaToxicologicaColorAttribute()
    {
        return match($this->categoria_toxicologica) {
            'Ia' => 'danger',      // Extremadamente peligroso
            'Ib' => 'danger',      // Altamente peligroso
            'II' => 'warning',     // Moderadamente peligroso
            'III' => 'info',       // Ligeramente peligroso
            'IV' => 'success',     // Productos que normalmente no ofrecen peligro
            default => 'secondary'
        };
    }

    /**
     * Accessor para cultivos formateados
     */
    public function getCultivosTextoAttribute()
    {
        if (empty($this->cultivos_objetivo)) {
            return 'Sin cultivos especificados';
        }

        return implode(', ', $this->cultivos_objetivo);
    }

    /**
     * Accessor para plagas formateadas
     */
    public function getPlagasTextoAttribute()
    {
        if (empty($this->plagas_objetivo)) {
            return 'Sin plagas especificadas';
        }

        return implode(', ', $this->plagas_objetivo);
    }
}
