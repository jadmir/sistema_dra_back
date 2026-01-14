<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Log;

class FertilizantesExportArray implements FromArray, WithTitle, WithColumnWidths, WithStyles, WithEvents
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

    public function array(): array
    {
        $rows = [];

        // Fila 1: Logo SIEA (texto simulado)
        $rows[] = ['SIEA', '', '', '', '', '', '', ''];

        // Fila 2: Encabezado principal
        $rows[] = ['Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP', '', '', '', '', '', '', 'F-'];

        // Fila 3: Subtítulo
        $rows[] = ['Dirección de Estadística e Información Agraria - DEIA', '', '', '', '', '', '', '4'];

        // Fila 4: Título del formulario
        $rows[] = ['PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGANICOS (S/.)'];

        // Fila 5: Espacio
        $rows[] = [''];

        // Fila 6: Sección I - Ubicación política (encabezado)
        $rows[] = ['I. Ubicación política', '', '', '', '', 'II. Año y mes de referencia'];

        // Fila 7: Ubicación - Región
        $rows[] = [
            '1. Región',
            $this->filtros['region'] ?? '',
            '',
            '',
            '',
            'Año',
            $this->filtros['anio'] ?? ''
        ];

        // Fila 8: Ubicación - Provincia
        $rows[] = [
            '2. Provincia',
            $this->filtros['provincia'] ?? '',
            '',
            '',
            '',
            'Mes',
            $this->filtros['mes_nombre'] ?? ''
        ];

        // Fila 9: Ubicación - Distrito
        $rows[] = ['3. Distrito', $this->filtros['distrito'] ?? ''];

        // Fila 10: Espacio
        $rows[] = [''];

        // Fila 11: Título de sección III
        $rows[] = ['III. Información de precios de principales fertilizantes y abonos orgánicos'];

        // Fila 12: Encabezados de tabla
        $rows[] = [
            'TIPO',
            'PRODUCTOS',
            'PRECIO PROMEDIO (S/.)',
            'PRECIO MINIMO (S/.)',
            'PRECIO MAXIMO (S/.)',
            'REGISTROS'
        ];

        // Verificar si hay datos
        if (empty($this->datos) || collect($this->datos)->isEmpty()) {
            $rows[] = ['', 'No hay datos disponibles para el periodo seleccionado'];
            return $rows;
        }

        // Categorías
        $categorias = [
            'Nitrogenados' => '1. FERTILIZANTES QUIMICOS NITROGENADOS',
            'Fosfatados' => 'FOSFATADOS',
            'Potásicos' => 'POTASICOS',
            'Compuestos' => 'COMPUESTOS',
            'Orgánicos' => '2. ABONOS ORGANICOS'
        ];

        // Convertir a collection si es array
        $datosCollection = collect($this->datos);
        $agrupadoPorCategoria = $datosCollection->groupBy('categoria');

        // Procesar cada categoría
        foreach ($categorias as $codigo => $nombre) {
            if (isset($agrupadoPorCategoria[$codigo]) && $agrupadoPorCategoria[$codigo]->isNotEmpty()) {
                // Título de categoría
                $rows[] = [$nombre, '', '', '', '', ''];

                // Productos de la categoría
                foreach ($agrupadoPorCategoria[$codigo] as $item) {
                    $rows[] = [
                        '',
                        $item['nombre'],
                        number_format($item['precio_promedio'], 2),
                        number_format($item['precio_minimo'], 2),
                        number_format($item['precio_maximo'], 2),
                        $item['num_registros']
                    ];
                }
            }
        }

        // Espacios antes de observaciones
        $rows[] = [''];
        $rows[] = [''];

        // Sección IV: Observaciones
        $rows[] = ['IV. Observaciones'];
        $rows[] = [''];
        $rows[] = [''];

        // Sección V: Encuestador y supervisor
        $rows[] = ['V. Del encuestador y supervisor', '', '', '', 'SUPERVISOR', '', 'Fecha de Supervisión'];
        $rows[] = ['ENCUESTADOR', '', '', '', 'Nombres'];
        $rows[] = ['Nombres', '', '', '', 'Apellidos'];
        $rows[] = ['Apellidos', '', '', '', 'Cargo'];
        $rows[] = ['Cargo', '', '', '', '', '', 'F2-EISA-DGESEP-DEIA'];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Logo SIEA (fila 1)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['rgb' => '1F4E78']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF2CC']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],

            // Encabezado principal (fila 2) - Fondo amarillo
            2 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFD966']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],

            // Subtítulo (fila 3)
            3 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFD966']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],

            // Título del formulario (fila 4)
            4 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],

            // Secciones I y II (fila 6)
            6 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E7E6E6']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ],

            // Datos de ubicación (filas 7, 8, 9)
            7 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ],
            8 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ],
            9 => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ],

            // Sección III (fila 11)
            11 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E7E6E6']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ],

            // Encabezados de tabla (fila 12)
            12 => [
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
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
                    ],
                ],
            ],
        ];
    }    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Ajustar altura de filas para mejor visualización
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(30);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(12)->setRowHeight(30);

                // === LOGO SIEA ===
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E78']
                        ],
                    ],
                ]);

                // === ENCABEZADO AMARILLO ===
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('A3:G3');
                $sheet->mergeCells('A4:G4');

                // Borde grueso al encabezado completo
                $sheet->getStyle('A1:H3')->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '000000']
                        ],
                    ],
                ]);

                // === F-4 EN LA ESQUINA ===
                $sheet->mergeCells('H2:H3');
                $sheet->getStyle('H2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 24,
                        'color' => ['rgb' => '8B4513']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFD966']
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                    ],
                ]);

                // === SECCIÓN I: UBICACIÓN POLÍTICA ===
                $sheet->mergeCells('A6:E6');
                $sheet->mergeCells('B7:E7');
                $sheet->mergeCells('B8:E8');
                $sheet->mergeCells('B9:E9');

                $sheet->getStyle('A6:E9')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                    ],
                ]);

                // === SECCIÓN II: AÑO Y MES ===
                $sheet->mergeCells('F6:G6');

                $sheet->getStyle('F6:G8')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                    ],
                ]);

                // === SECCIÓN III: TÍTULO ===
                $sheet->mergeCells('A11:F11');

                // === TABLA DE PRODUCTOS ===
                $lastRow = $sheet->getHighestRow();

                // Aplicar bordes a toda la tabla
                $sheet->getStyle('A12:F' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ],
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '000000']
                        ],
                    ],
                ]);

                // Aplicar formato a las filas de categorías y productos
                for ($i = 13; $i <= $lastRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();

                    if (!empty($cellValue) && $cellValue !== '') {
                        // Es una categoría - negrita y fondo gris claro
                        $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 10
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'D9E1F2']
                            ],
                        ]);
                        $sheet->mergeCells('A' . $i . ':F' . $i);
                    } else {
                        // Es un producto - alineación y tamaño normal
                        $sheet->getStyle('B' . $i)->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                        ]);
                        $sheet->getStyle('C' . $i . ':F' . $i)->applyFromArray([
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                        ]);
                    }
                }

                // === AGREGAR FILAS ZEBRA (ALTERNAS) PARA PRODUCTOS ===
                $isProductRow = false;
                $rowColor = 'FFFFFF';

                for ($i = 13; $i <= $lastRow; $i++) {
                    $cellValue = $sheet->getCell('A' . $i)->getValue();

                    if (empty($cellValue) || $cellValue === '') {
                        // Es un producto
                        $rowColor = $isProductRow ? 'F2F2F2' : 'FFFFFF';
                        $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $rowColor]
                            ],
                        ]);
                        $isProductRow = !$isProductRow;
                    } else {
                        // Reset para nueva categoría
                        $isProductRow = false;
                    }
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Fertilizantes F-4';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 28,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 12,
            'G' => 12,
            'H' => 8,
        ];
    }
}
