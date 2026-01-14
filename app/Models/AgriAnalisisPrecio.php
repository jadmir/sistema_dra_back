<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgriAnalisisPrecio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_analisis_precios';

    protected $fillable = [
        'tipo_formulario',
        'producto_id',
        'producto_nombre',
        'producto_categoria',
        'fecha',
        'año',
        'mes',
        'semana',
        'region',
        'provincia',
        'distrito',
        'precio_promedio',
        'precio_minimo',
        'precio_maximo',
        'precio_mediana',
        'desviacion_estandar',
        'coeficiente_variacion',
        'num_registros',
        'num_encuestas',
        'num_casas_comerciales',
        'num_transportistas',
        'precio_periodo_anterior',
        'variacion_absoluta',
        'variacion_porcentual',
        'tendencia',
        'fecha_calculo',
        'version'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_promedio' => 'decimal:2',
        'precio_minimo' => 'decimal:2',
        'precio_maximo' => 'decimal:2',
        'precio_mediana' => 'decimal:2',
        'desviacion_estandar' => 'decimal:2',
        'coeficiente_variacion' => 'decimal:2',
        'precio_periodo_anterior' => 'decimal:2',
        'variacion_absoluta' => 'decimal:2',
        'variacion_porcentual' => 'decimal:2',
        'fecha_calculo' => 'datetime'
    ];

    // Scopes
    public function scopeTipoFormulario($query, $tipo)
    {
        return $query->where('tipo_formulario', $tipo);
    }

    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopePeriodo($query, $año, $mes)
    {
        return $query->where('año', $año)->where('mes', $mes);
    }

    public function scopeProvincia($query, $provincia)
    {
        return $query->where('provincia', $provincia);
    }

    public function scopeTendencia($query, $tendencia)
    {
        return $query->where('tendencia', $tendencia);
    }

    // Accessors
    public function getVolatilidadAttribute()
    {
        if (!$this->coeficiente_variacion) {
            return 'desconocida';
        }

        if ($this->coeficiente_variacion < 10) {
            return 'baja';
        } elseif ($this->coeficiente_variacion < 20) {
            return 'media';
        } else {
            return 'alta';
        }
    }

    public function getVariacionTextoAttribute()
    {
        if (!$this->variacion_porcentual) {
            return 'Sin datos del período anterior';
        }

        $porcentaje = abs($this->variacion_porcentual);
        $direccion = $this->variacion_porcentual > 0 ? 'aumentó' : 'disminuyó';

        return "El precio {$direccion} {$porcentaje}% respecto al período anterior";
    }
}
