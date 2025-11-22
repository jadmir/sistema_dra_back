<?php

namespace App\Exports;

use App\Models\AgriNatalidadMortalidad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgriNatalidadMortalidadExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AgriNatalidadMortalidad::query();

        if (!empty($this->filters['search'])) {
            $search = trim($this->filters['search']);
            $query->where(function ($q) use ($search) {
                $q->where('concepto', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%");
            });
        }

        if (isset($this->filters['estado']) && $this->filters['estado'] !== '') {
            $estado = filter_var($this->filters['estado'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($estado)) {
                $query->where('estado', $estado);
            }
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Concepto',
            'Observaciones',
            'Usuario',
            'Estado',
            'Fecha',
        ];
    }

    public function map($registro): array
    {
        return [
            $registro->id,
            $registro->concepto,
            $registro->observaciones,
            $registro->usuario ? $registro->usuario->nombre : 'Sin usuario',
            $registro->estado ? 'Activo' : 'Inactivo',
            optional($registro->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 1);
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'REPORTE DE NATALIDAD Y MORTALIDAD');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('f2f2f2');

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
