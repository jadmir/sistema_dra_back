<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriFertilizante extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_fertilizantes_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre_comercial',
        'tipo',
        'composicion_quimica',
        'concentracion_npk',
        'presentacion',
        'unidad_medida',
        'registro_senasa',
        'activo',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'composicion_quimica' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Scope para fertilizantes activos
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
     * Scope para filtrar por presentación
     */
    public function scopePresentacion($query, $presentacion)
    {
        return $query->where('presentacion', $presentacion);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre_comercial', 'like', "%{$termino}%");
    }

    /**
     * Accessor para composición formateada
     */
    public function getComposicionTextoAttribute()
    {
        if (empty($this->composicion_quimica)) {
            return $this->concentracion_npk ?? 'Sin composición especificada';
        }

        $composicion = [];
        foreach ($this->composicion_quimica as $elemento => $porcentaje) {
            $composicion[] = "{$elemento}: {$porcentaje}%";
        }

        return implode(', ', $composicion);
    }

    /**
     * Accessor para nombre con presentación
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre_comercial;

        if ($this->concentracion_npk) {
            $nombre .= " ({$this->concentracion_npk})";
        }

        if ($this->presentacion) {
            $nombre .= " - {$this->presentacion}";
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
            'nitrogenado' => 'Nitrogenado',
            'fosfatado' => 'Fosfatado',
            'potasico' => 'Potásico',
            'compuesto_npk' => 'Compuesto NPK',
            'organico' => 'Orgánico',
            'micronutrientes' => 'Micronutrientes',
            'foliar' => 'Foliar',
            default => ucfirst($this->tipo)
        };
    }
}
