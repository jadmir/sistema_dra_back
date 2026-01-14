<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriTipoMaquinaria extends Model
{
    use SoftDeletes;

    /**
     * Tabla asociada al modelo
     */
    protected $table = 'agri_tipo_maquinaria_insumos';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'nombre',
        'categoria',
        'subcategoria',
        'especificaciones_tecnicas',
        'unidad_medida',
        'activo',
        'observaciones',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'especificaciones_tecnicas' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Scope para maquinaria activa
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para filtrar por subcategoría
     */
    public function scopeSubcategoria($query, $subcategoria)
    {
        return $query->where('subcategoria', $subcategoria);
    }

    /**
     * Accessor para especificaciones formateadas
     */
    public function getEspecificacionesTextoAttribute()
    {
        if (empty($this->especificaciones_tecnicas)) {
            return 'Sin especificaciones';
        }

        $specs = [];
        foreach ($this->especificaciones_tecnicas as $key => $value) {
            $specs[] = ucfirst($key) . ': ' . $value;
        }

        return implode(' | ', $specs);
    }

    /**
     * Accessor para nombre completo con categoría
     */
    public function getNombreCompletoAttribute()
    {
        $nombre = $this->nombre;

        if ($this->subcategoria) {
            $nombre .= " ({$this->subcategoria})";
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
}
