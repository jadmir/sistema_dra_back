<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RegistroAgricolaExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $registro;

    public function __construct($registro)
    {
        $this->registro = $registro;
    }

    public function view(): View
    {
        return view('reportes.registro_agricola_excel', [
            'registro' => $this->registro
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:Z500')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 10],
                    'alignment' => [
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'wrapText' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ]);
            }
        ];
    }
}
