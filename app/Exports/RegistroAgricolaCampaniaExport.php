<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RegistroAgricolaCampaniaExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $registros;
    protected $anioInicio;
    protected $anioFin;
    protected $mesInicio;
    protected $mesFin;

    protected $region;
    protected $provincia;
    protected $distrito;

    public function __construct(
        $registros,
        $anioInicio,
        $anioFin,
        $mesInicio,
        $mesFin,
        $region,
        $provincia,
        $distrito,
    ) {
        $this->registros  = $registros;
        $this->anioInicio = $anioInicio;
        $this->anioFin    = $anioFin;
        $this->mesInicio  = $mesInicio;
        $this->mesFin     = $mesFin;

        $this->region     = $region;
        $this->provincia  = $provincia;
        $this->distrito   = $distrito;
    }

    public function view(): View
    {
        $mesesMap = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic',
        ];

        return view('reportes.registro_agricola_campania', [
            'registros'  => $this->registros,
            'anioInicio' => $this->anioInicio,
            'anioFin'    => $this->anioFin,
            'mesInicio'  => $this->mesInicio,
            'mesFin'     => $this->mesFin,
            'mesesMap'   => $mesesMap,

            'region'     => $this->region,
            'provincia'  => $this->provincia,
            'distrito'   => $this->distrito,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastRow    = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->applyFromArray([
                        'font' => [
                            'name' => 'Calibri',
                            'size' => 10,
                        ],
                        'alignment' => [
                            'vertical'   => 'center',
                            'horizontal' => 'center',
                            'wrapText'   => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color'       => ['rgb' => '2E7D32']
                            ]
                        ]
                    ]);

                $sheet->getStyle("A1:{$lastColumn}4")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 12,
                        ],
                        'fill' => [
                            'fillType' => 'solid',
                            'color'    => ['rgb' => 'C8E6C9']
                        ],
                    ]);

                $sheet->getStyle("A5:{$lastColumn}5")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'fill' => [
                            'fillType' => 'solid',
                            'color'    => ['rgb' => 'A5D6A7']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                'color'       => ['rgb' => '1B5E20']
                            ]
                        ]
                    ]);

                /*total producto*/
                for ($row = 6; $row <= $lastRow - 1; $row++) {

                    $cellValue = $sheet->getCell("A{$row}")->getValue();

                    if ($cellValue === "TOTAL") {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")
                            ->applyFromArray([
                                'font' => ['bold' => true],
                                'fill' => [
                                    'fillType' => 'solid',
                                    'color'    => ['rgb' => 'E8F5E9']
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                                        'color'       => ['rgb' => '2E7D32']
                                    ]
                                ]
                            ]);
                    }
                }

                /*total general*/
                $sheet->getStyle("A{$lastRow}:{$lastColumn}{$lastRow}")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                            'size' => 11
                        ],
                        'fill' => [
                            'fillType' => 'solid',
                            'color'    => ['rgb' => '2E7D32']
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color'       => ['rgb' => '1B5E20']
                            ]
                        ]
                    ]);
            }
        ];
    }
}
