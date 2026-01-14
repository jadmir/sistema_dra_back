<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FertilizantesExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $datos;
    protected $filtros;
    protected $estadisticas;

    public function __construct($datos, $filtros = [], $estadisticas = [])
    {
        $this->datos = $datos;
        $this->filtros = $filtros;
        $this->estadisticas = $estadisticas;
    }

    public function view(): View
    {
        return view('exports.fertilizantes_reporte_simple', [
            'datos' => $this->datos,
            'filtros' => $this->filtros,
            'estadisticas' => $this->estadisticas,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // ============================================
                // ENCABEZADO PRINCIPAL (Filas 1-3)
                // ============================================

                // Logo SIEA en A1
                $sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => ['rgb' => '8B4513']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Título principal DGESEP (B1:K1)
                $sheet->getStyle('B1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '8B4513']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                        'right' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]
                    ]
                ]);

                // Subtítulo DEIA (B2:K2)
                $sheet->getStyle('B2:K2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'right' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]
                    ]
                ]);

                // Título del formulario (B3:K3)
                $sheet->getStyle('B3:K3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']],
                        'right' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]
                    ]
                ]);

                // Código F-4 vertical en L1:L3
                $sheet->mergeCells('L1:L3');
                $sheet->getStyle('L1:L3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 28,
                        'color' => ['rgb' => '8B4513']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC107']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'textRotation' => 90
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '8B4513']
                        ]
                    ]
                ]);

                // Fusionar celdas del encabezado principal
                $sheet->mergeCells('A1:A3');
                $sheet->mergeCells('B1:K1');
                $sheet->mergeCells('B2:K2');
                $sheet->mergeCells('B3:K3');

                // Altura de filas del encabezado
                $sheet->getRowDimension(1)->setRowHeight(22);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(28);

                // ============================================
                // SECCIÓN I: UBICACIÓN (Fondo amarillo claro)
                // ============================================

                $rowSeccion1 = 4;
                $sheet->getStyle("A{$rowSeccion1}:L{$rowSeccion1}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE082']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Fondo blanco para datos de ubicación (fila 5)
                $rowUbicacion = 5;
                $sheet->getStyle("A{$rowUbicacion}:L{$rowUbicacion}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ============================================
                // SECCIÓN II: PERIODO (Fondo amarillo claro)
                // ============================================

                $rowSeccion2 = 6;
                $sheet->getStyle("A{$rowSeccion2}:L{$rowSeccion2}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE082']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Fondo blanco para datos de periodo (fila 7)
                $rowPeriodo = 7;
                $sheet->getStyle("A{$rowPeriodo}:L{$rowPeriodo}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFFFF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ============================================
                // SECCIÓN III: PRECIOS (Encabezados de tabla)
                // ============================================

                $rowSeccion3 = 8;
                $sheet->getStyle("A{$rowSeccion3}:L{$rowSeccion3}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFE082']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Encabezados de columnas de la tabla (fila 9)
                $rowEncabezado = 9;
                $sheet->getStyle("A{$rowEncabezado}:L{$rowEncabezado}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 9,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '8B4513']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Altura de la fila de encabezados
                $sheet->getRowDimension($rowEncabezado)->setRowHeight(40);

                // ============================================
                // DATOS DE LA TABLA (Categorías y precios)
                // ============================================

                // Buscar filas de categorías y aplicar estilo
                $categorias = [
                    'NITROGENADOS',
                    'FOSFATADOS',
                    'POTÁSICOS',
                    'COMPUESTOS',
                    'ORGÁNICOS'
                ];

                for ($row = 10; $row <= $highestRow; $row++) {
                    $tipoValue = $sheet->getCell("A{$row}")->getValue();

                    // Si es una categoría, aplicar estilo especial
                    $esCategoria = false;
                    if ($tipoValue !== null && $tipoValue !== '') {
                        foreach ($categorias as $cat) {
                            if (stripos($tipoValue, $cat) !== false || strtoupper($tipoValue) === $cat) {
                                $esCategoria = true;
                                break;
                            }
                        }
                    }

                    if ($esCategoria) {
                        // Fila de categoría: fondo amarillo suave
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 10,
                                'color' => ['rgb' => '8B4513']
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFFDE7']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000']
                                ]
                            ]
                        ]);
                    } else {
                        // Fila de datos normal: fondo blanco
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FFFFFF']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000']
                                ]
                            ]
                        ]);

                        // Centrar precios (columnas D a L)
                        $sheet->getStyle("D{$row}:L{$row}")->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER
                            ]
                        ]);
                    }

                    // Altura de fila estándar
                    $sheet->getRowDimension($row)->setRowHeight(18);
                }

                // ============================================
                // ANCHOS DE COLUMNA
                // ============================================

                $sheet->getColumnDimension('A')->setWidth(18);  // Tipo
                $sheet->getColumnDimension('B')->setWidth(28);  // Fertilizante/Abono
                $sheet->getColumnDimension('C')->setWidth(15);  // Envase
                $sheet->getColumnDimension('D')->setWidth(12);  // Casa 1
                $sheet->getColumnDimension('E')->setWidth(12);  // Casa 2
                $sheet->getColumnDimension('F')->setWidth(12);  // Casa 3
                $sheet->getColumnDimension('G')->setWidth(12);  // Casa 4
                $sheet->getColumnDimension('H')->setWidth(12);  // Casa 5
                $sheet->getColumnDimension('I')->setWidth(12);  // Casa 6
                $sheet->getColumnDimension('J')->setWidth(12);  // Casa 7
                $sheet->getColumnDimension('K')->setWidth(12);  // Casa 8
                $sheet->getColumnDimension('L')->setWidth(15);  // Precio promedio

                // ============================================
                // BORDES EXTERIORES GRUESOS
                // ============================================

                $sheet->getStyle("A1:L{$highestRow}")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);
            }
        ];
    }
}
