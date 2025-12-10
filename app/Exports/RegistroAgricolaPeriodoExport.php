<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RegistroAgricolaPeriodoExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $registros;
    protected $anioInicio;
    protected $mesInicio;
    protected $anioFin;
    protected $mesFin;

    public function __construct($registros, $anioInicio, $mesInicio, $anioFin, $mesFin)
    {
        $this->registros = $registros;
        $this->anioInicio = $anioInicio;
        $this->mesInicio = $mesInicio;
        $this->anioFin = $anioFin;
        $this->mesFin = $mesFin;
    }

    public function view(): View
    {
        return view('reportes.registro_agricola_periodo', [
            'registros' => $this->registros,
            'anioInicio' => $this->anioInicio,
            'mesInicio' => $this->mesInicio,
            'anioFin' => $this->anioFin,
            'mesFin' => $this->mesFin,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Estilo general
                $sheet->getStyle('A1:ZZ500')->applyFromArray([
                    'font' => ['name' => 'Calibri', 'size' => 10],
                    'alignment' => ['vertical' => 'center', 'horizontal' => 'center', 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                ]);

                // principales
                $sheet->getStyle('A1:ZZ2')->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2e7d32']],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                ]);

                $sheet->getStyle('D2:ZZ2')->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '81c784']],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                ]);

                //cultivo
                $currentRow = 3;
                foreach ($this->registros as $registro) {
                    $variablesCount = count($registro['variables']);
                    if ($variablesCount > 1) {
                        $startRow = $currentRow;
                        $endRow = $currentRow + $variablesCount - 1;
                        $sheet->mergeCells("A{$startRow}:A{$endRow}");
                    }
                    $currentRow += $variablesCount;
                }
            }
        ];
    }
}
