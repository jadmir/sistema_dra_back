<?php

namespace App\Exports;

use App\Models\AgriNatalidadMortalidad;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgriNatalidadMortalidadExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;

    public function __construct()
    {
        $this->data = AgriNatalidadMortalidad::where('estado', true)
            ->with('usuario')
            ->get()
            ->map(function ($registro) {
                return [
                    'id' => $registro->id,
                    'concepto' => $registro->concepto,
                    'observaciones' => $registro->observaciones,
                    'Usuario' => $registro->usuario ? $registro->usuario->nombre : 'Sin usuario',
                    'estado' => 'Activo',
                ];
            })
            ->toArray();
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Concepto',
            'Observaciones',
            'Usuario',
            'Estado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->insertNewRowBefore(1, 1);
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'REPORTE DE NATALIDAD Y MORTALIDAD');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A2:E2')->getFont()->setBold(true);
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
