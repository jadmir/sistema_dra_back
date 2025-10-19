<?php

namespace App\Exports;

use App\Models\Cultivo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CultivosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $filters = $this->filters;

        $q = Cultivo::query()
            ->with(['subgrupo.grupo.subsector'])
            ->where('estado', 1);

        if (!empty($filters['cultivo_id'])) {
            $q->where('id', (int)$filters['cultivo_id']);
        }
        if (!empty($filters['sub_grupo_id'])) {
            $q->where('sub_grupo_id', (int)$filters['sub_grupo_id']);
        }
        if (!empty($filters['grupo_id'])) {
            $q->whereHas('subgrupo', fn($w) => $w->where('grupo_id', (int)$filters['grupo_id']));
        }
        if (!empty($filters['sub_sector_id'])) {
            $q->whereHas('subgrupo.grupo', fn($w) => $w->where('sub_sector_id', (int)$filters['sub_sector_id']));
        }
        if (!empty($filters['search'])) {
            $term = trim((string)$filters['search']);
            $q->where(function ($w) use ($term) {
                $w->where('codigo', 'like', "%{$term}%")
                  ->orWhere('descripcion', 'like', "%{$term}%");
            });
        }

        return $q->orderBy('codigo');
    }

    public function headings(): array
    {
        return ['Código', 'Descripción', 'Subgrupo', 'Grupo', 'Subsector', 'Creado'];
    }

    public function map($c): array
    {
        return [
            $c->codigo,
            $c->descripcion,
            $c->subgrupo?->codigo,
            $c->subgrupo?->grupo?->codigo,
            $c->subgrupo?->grupo?->subsector?->codigo,
            optional($c->created_at)->format('Y-m-d H:i'),
        ];
    }
}
