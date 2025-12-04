<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Models\AgriRegistroPecuario;

class RegistroPecuarioFilter
{
    protected $request;

    protected $filters = [
        'variedad',
        'tipo',
        'search'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($query = null)
    {
        if ($query === null) {
            $query = AgriRegistroPecuario::with([
                'animales.variedad',
                'animalTotal',
                'productosLeche.destino',
                'lechefresca',
                'sacaReproduccion.variedad',
                'sacaVacunoDescarte.variedad',
                'natalidad.natalidadMortalidad',
                'mortalidad.variedad',
                'informeTecnico'
            ]);
        }

        foreach ($this->filters as $filter) {

            if ($filter === 'tipo') {
                $query = $this->tipo($query);
                continue;
            }

            if (method_exists($this, $filter) && $this->request->filled($filter)) {
                $query = $this->{$filter}($query);
            }
        }

        if ($this->request->filled('fecha_inicio') && $this->request->filled('fecha_fin')) {
            $query = $this->fecha($query);
        }

        return $query;
    }

    /**Filtro por fechas */
    private function fecha($query)
    {
        $inicio = $this->request->fecha_inicio . ' 00:00:00';
        $fin = $this->request->fecha_fin . ' 23:59:59';

        return $query->whereBetween('created_at', [$inicio, $fin]);
    }
    /*Filtro variedad     */
    private function variedad($query)
    {
        $variedad = $this->request->variedad;

        if (!$variedad || $variedad === 'all') {
            return $query;
        }
        return $query->whereHas('animales', function ($q) use ($variedad) {
            $q->where('variedad_id', $variedad);
        });
    }

    /*Filtro por tipo*/
    private function tipo($query)
    {
        if (!$this->request->has('tipo') || $this->request->tipo === null || $this->request->tipo === '') {
            return $query;
        }

        return match ($this->request->tipo) {
            'natalidad'         => $query->whereHas('natalidad'),
            'mortalidad'        => $query->whereHas('mortalidad'),
            'saca'              => $query->where(function ($q) {
                $q->whereHas('sacaReproduccion')
                    ->orWhereHas('sacaVacunoDescarte');
            }),
            'produccion_leche'  => $query->whereHas('productosLeche'),
            default             => $query
        };
    }

    /*busqueda general*/
    private function search($query)
    {
        $s = $this->request->search;

        return $query->where(function ($q) use ($s) {
            $q->where('nombre_establo', 'LIKE', "%$s%")
                ->orWhere('codigo_establo', 'LIKE', "%$s%")
                ->orWhere('producto_razon_social', 'LIKE', "%$s%")
                ->orWhereHas('animales', function ($qa) use ($s) {
                    $qa->whereHas('variedad', function ($v) use ($s) {
                        $v->where('nombre', 'LIKE', "%$s%");
                    });
                })
                ->orWhereHas('productosLeche', function ($ql) use ($s) {
                    $ql->whereHas('destino', function ($d) use ($s) {
                        $d->where('nombre', 'LIKE', "%$s%")
                            ->orWhere('descripcion', 'LIKE', "%$s%");
                    });
                });
        });
    }
}
