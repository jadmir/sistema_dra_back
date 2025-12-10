<?php

namespace App\Exports;

use App\Models\AgriRegistro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgriRegistroDetalleExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = AgriRegistro::query()->with(['distrito.provincia.region']);

        // Filtros por región/provincia/distrito/año
        if (!empty($this->request->region_id)) {
            $query->where('region_id', $this->request->region_id);
        }
        if (!empty($this->request->provincia_id)) {
            $query->where('provincia_id', $this->request->provincia_id);
        }
        if (!empty($this->request->distrito_id)) {
            $query->where('distrito_id', $this->request->distrito_id);
        }
        if (!empty($this->request->anio)) {
            $query->where('anio', $this->request->anio);
        }

        // Filtro de búsqueda
        if (!empty($this->request->search)) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo_establo', 'like', "%$search%")
                    ->orWhere('producto', 'like', "%$search%")
                    ->orWhere('nombre_establo', 'like', "%$search%");
            });
        }

        $registros = $query->get();

        // Mapear datos para Excel
        return $registros->map(function ($item) {
            return [
                'Código Establo' => $item->codigo_establo,
                'Producto' => $item->producto,
                'Región' => $item->distrito->provincia->region->nombre ?? '',
                'Provincia' => $item->distrito->provincia->nombre ?? '',
                'Distrito' => $item->distrito->nombre ?? '',
                'Superficie Sembrada' => $item->superficie_sembrada,
                'Superficie Cosechada' => $item->superficie_cosechada,
                'Producción Total' => $item->produccion_total,
                'Precio Chacra Promedio' => $item->produccion_total > 0 ? $item->valor_total / $item->produccion_total : 0,
                'Rendimiento' => $item->superficie_cosechada > 0 ? $item->produccion_total / $item->superficie_cosechada : 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Código Establo',
            'Producto',
            'Región',
            'Provincia',
            'Distrito',
            'Superficie Sembrada',
            'Superficie Cosechada',
            'Producción Total',
            'Precio Chacra Promedio',
            'Rendimiento',
        ];
    }
}
