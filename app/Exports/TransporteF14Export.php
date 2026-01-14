<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Log;

class TransporteF14Export implements FromArray, WithEvents, WithColumnWidths
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
        $data = [];

        // ENCABEZADO PRINCIPAL (Filas 1-3)
        $data[] = ['SIEA', '', '', 'Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP', '', '', '', '', '', '', '', 'F-14'];
        $data[] = ['Sistema Integrado de', '', '', 'Dirección de Estadística e Información Agraria - DEIA', '', '', '', '', '', '', '', ''];
        $data[] = ['Estadística Agraria', '', '', 'PRECIOS (S/.) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS', '', '', '', '', '', '', '', ''];

        // Fila 4: Espacio
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // SECCIONES I Y II (Filas 5-8)
        $data[] = ['I. Ubicación política', '', '', '', '', '', '', 'II. Año y mes de referencia', '', '', '', ''];
        $data[] = ['1. Región', $this->filtros['region'] ?? 'Todas las regiones', '', '', '', '', '', 'Año', $this->filtros['año'] ?? date('Y'), '', '', ''];
        $data[] = ['2. Provincia', $this->filtros['provincia'] ?? 'Todas las provincias', '', '', '', '', '', 'Mes', $this->filtros['mes_nombre'] ?? '', '', '', ''];
        $data[] = ['3. Distrito', $this->filtros['distrito'] ?? 'Todos los distritos', '', '', '', '', '', '', '', '', '', ''];

        // Fila 9: Espacio
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // SECCIÓN III: TABLA (Fila 10)
        $data[] = ['III. Información de precios de transporte de productos', '', '', '', '', '', '', '', '', '', '', ''];

        // Fila 11: Espacio para la tabla
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // ENCABEZADOS DE TABLA (Filas 12-13)
        $data[] = ['Tipo', 'Origen', 'Destino', 'Vía:', 'Producto', 'Unidad de medida', 'Precio con IGV (S/. x UM) -', '', '', '', '', ''];
        $data[] = ['', '', '', 'a. Terrestre', '(Agrícola, Agroindustrial,', '', 'Mínimo', 'Máximo', '', '', '', ''];
        $data[] = ['', '', '', 'b. Fluvial', 'Pecuario)', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', 'c. Aéreo', '', '', '', '', '', '', '', ''];

        // Fila: "Dentro de la región:"
        $data[] = ['Dentro de la región:', '', '', '', '', '', '', '', '', '', '', ''];

        // Determinar si hay datos filtrados
        $hayDentro = false;
        $hayFuera = false;

        foreach ($this->datos as $ruta) {
            $origen = $ruta->origen ?? '';
            $tipo = $ruta->tipo_vehiculo ?? $ruta->producto_categoria ?? '';

            // Si origen o destino contiene ciudades principales de la región, es "dentro"
            // Si es "Transporte" o "Flete", probablemente es "fuera"
            if (stripos($tipo, 'transporte') !== false || stripos($tipo, 'flete') !== false) {
                $hayFuera = true;
            } else {
                $hayDentro = true;
            }
        }

        // DATOS DENTRO DE LA REGIÓN
        if ($hayDentro) {
            foreach ($this->datos as $ruta) {
                $tipo = $ruta->tipo_vehiculo ?? $ruta->producto_categoria ?? '';
                if (stripos($tipo, 'transporte') === false && stripos($tipo, 'flete') === false) {
                    $data[] = [
                        $tipo,
                        $ruta->origen ?? '',
                        $ruta->destino ?? '',
                        'a. Terrestre',
                        $ruta->producto ?? $ruta->producto_nombre ?? '',
                        $ruta->unidad_medida ?? 'Tonelada',
                        number_format($ruta->precio_minimo ?? 0, 2),
                        number_format($ruta->precio_maximo ?? 0, 2),
                        '',
                        '',
                        '',
                        ''
                    ];
                }
            }
        }

        // Fila: "Fuera de la región:"
        if ($hayFuera) {
            $data[] = ['Fuera de la región:', '', '', '', '', '', '', '', '', '', '', ''];

            foreach ($this->datos as $ruta) {
                $tipo = $ruta->tipo_vehiculo ?? $ruta->producto_categoria ?? '';
                if (stripos($tipo, 'transporte') !== false || stripos($tipo, 'flete') !== false) {
                    $data[] = [
                        $tipo,
                        $ruta->origen ?? '',
                        $ruta->destino ?? '',
                        'a. Terrestre',
                        $ruta->producto ?? $ruta->producto_nombre ?? '',
                        $ruta->unidad_medida ?? 'Tonelada',
                        number_format($ruta->precio_minimo ?? 0, 2),
                        number_format($ruta->precio_maximo ?? 0, 2),
                        '',
                        '',
                        '',
                        ''
                    ];
                }
            }
        }

        // Si no hay clasificación, mostrar todos
        if (!$hayDentro && !$hayFuera) {
            foreach ($this->datos as $ruta) {
                $data[] = [
                    $ruta->tipo_vehiculo ?? $ruta->producto_categoria ?? '',
                    $ruta->origen ?? '',
                    $ruta->destino ?? '',
                    'a. Terrestre',
                    $ruta->producto ?? $ruta->producto_nombre ?? '',
                    $ruta->unidad_medida ?? 'Tonelada',
                    number_format($ruta->precio_minimo ?? 0, 2),
                    number_format($ruta->precio_maximo ?? 0, 2),
                    '',
                    '',
                    '',
                    ''
                ];
            }
        }

        // Espacios antes de observaciones
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // IV. OBSERVACIONES
        $data[] = ['IV. Observaciones', '', '', '', '', '', '', '', '', '', '', ''];
        $observaciones = '- Total de rutas registradas: ' . ($this->estadisticas['rutas_distintas'] ?? 0) .
                        ' - Costo promedio general: S/. ' . number_format($this->estadisticas['precio_promedio_general'] ?? 0, 2);
        $data[] = [$observaciones, '', '', '', '', '', '', '', '', '', '', ''];

        // Espacios
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // V. DEL ENCUESTADOR Y SUPERVISOR
        $data[] = ['V. Del encuestador y supervisor', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['ENCUESTADOR', '', '', '', '', 'SUPERVISOR', '', '', '', '', 'Fecha de supervisión:', ''];
        $data[] = ['Nombres', $this->filtros['encuestador_nombre'] ?? '', '', '', '', 'Nombres', $this->filtros['supervisor_nombre'] ?? '', '', '', '',
                   !empty($this->filtros['fecha_validacion']) ? \Carbon\Carbon::parse($this->filtros['fecha_validacion'])->format('d/m/Y') : '', ''];
        $data[] = ['Apellidos', $this->filtros['encuestador_apellido'] ?? '', '', '', '', 'Apellidos', $this->filtros['supervisor_apellido'] ?? '', '', '', '', '', ''];
        $data[] = ['Cargo', $this->filtros['encuestador_cargo'] ?? '', '', '', '', 'Cargo', $this->filtros['supervisor_cargo'] ?? '', '', '', '', 'Firma', ''];

        // Footer
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', 'F7-EISA-DGESEP-DEIA'];

        Log::info("TransporteF14Export - Total filas: " . count($data));

        return $data;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 18,
            'C' => 18,
            'D' => 15,
            'E' => 35,  // Producto más ancho
            'F' => 15,
            'G' => 13,  // Precio mínimo
            'H' => 13,  // Precio máximo
            'I' => 12,  // Año más ancho para evitar "##"
            'J' => 15,  // Mes más ancho
            'K' => 22,  // Fecha supervisión
            'L' => 22,  // Firma y footer
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ENCABEZADO PRINCIPAL (Filas 1-3)
                // Merge del logo SIEA
                $sheet->mergeCells('A1:C3');

                // Merge del título principal
                $sheet->mergeCells('D1:K1');
                $sheet->mergeCells('D2:K2');
                $sheet->mergeCells('D3:K3');

                // Badge F-14
                $sheet->mergeCells('L1:L3');

                // Estilos del encabezado
                $sheet->getStyle('A1:L3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]]
                ]);

                // Logo SIEA - estilo especial
                $sheet->getStyle('A1:C3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 18],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']]
                ]);

                // Título principal más grande
                $sheet->getStyle('D1:K3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 13],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']]
                ]);

                // Badge F-14 - estilo especial (naranja/rojo)
                $sheet->getStyle('L1:L3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF6B35']],
                    'font' => ['size' => 24, 'bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
                ]);

                // SECCIÓN I: Ubicación política (filas 5-8, columnas A-G)
                $sheet->mergeCells('A5:G5');
                $sheet->getStyle('A5:G8')->applyFromArray([
                    'borders' => [
                        'outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']],
                        'inside' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                $sheet->getStyle('A5')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // SECCIÓN II: Año y mes (filas 5-8, columnas H-L)
                $sheet->mergeCells('H5:L5');
                $sheet->getStyle('H5:L8')->applyFromArray([
                    'borders' => [
                        'outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']],
                        'inside' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
                    ]
                ]);
                $sheet->getStyle('H5')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // SECCIÓN III: Título
                $sheet->mergeCells('A10:L10');
                $sheet->getStyle('A10')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                ]);

                // ENCABEZADOS DE TABLA (filas 12-15)
                // Merges verticales para columnas que no cambian
                $sheet->mergeCells('A12:A15');
                $sheet->mergeCells('B12:B15');
                $sheet->mergeCells('C12:C15');
                $sheet->mergeCells('D12:D15');
                $sheet->mergeCells('E12:E15');
                $sheet->mergeCells('F12:F15');

                // Merge horizontal para "Precio con IGV"
                $sheet->mergeCells('G12:H12');
                $sheet->mergeCells('G13:G15');
                $sheet->mergeCells('H13:H15');

                // Estilos de encabezados de tabla
                $sheet->getStyle('A12:H15')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]
                ]);

                // Buscar la fila de IV. Observaciones
                $arrayData = $this->array();
                $totalFilas = count($arrayData);

                $filaObservaciones = null;
                $filaSeccionV = null;

                for ($i = 0; $i < $totalFilas; $i++) {
                    if (isset($arrayData[$i][0]) && strpos($arrayData[$i][0], 'IV. Observaciones') !== false) {
                        $filaObservaciones = $i + 1;
                    }
                    if (isset($arrayData[$i][0]) && strpos($arrayData[$i][0], 'V. Del encuestador') !== false) {
                        $filaSeccionV = $i + 1;
                    }
                }

                // IV. OBSERVACIONES
                if ($filaObservaciones) {
                    $sheet->mergeCells("A{$filaObservaciones}:L{$filaObservaciones}");
                    $sheet->mergeCells("A" . ($filaObservaciones + 1) . ":L" . ($filaObservaciones + 1));
                    $sheet->getStyle("A{$filaObservaciones}:L" . ($filaObservaciones + 1))->applyFromArray([
                        'borders' => ['outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP]
                    ]);
                    $sheet->getStyle("A{$filaObservaciones}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                    ]);
                }

                // V. DEL ENCUESTADOR Y SUPERVISOR
                if ($filaSeccionV) {
                    $sheet->mergeCells("A{$filaSeccionV}:L{$filaSeccionV}");
                    $sheet->getStyle("A{$filaSeccionV}:L" . ($totalFilas - 1))->applyFromArray([
                        'borders' => [
                            'outline' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']],
                            'inside' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
                        ]
                    ]);

                    // Título de sección V
                    $sheet->getStyle("A{$filaSeccionV}")->applyFromArray([
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
                    ]);

                    // Línea vertical divisoria entre ENCUESTADOR y SUPERVISOR
                    for ($row = $filaSeccionV + 1; $row < $totalFilas; $row++) {
                        $sheet->getStyle("F{$row}")->applyFromArray([
                            'borders' => ['left' => ['borderStyle' => Border::BORDER_THICK, 'color' => ['rgb' => '000000']]]
                        ]);
                    }

                    // Negrita en encabezados ENCUESTADOR/SUPERVISOR
                    $sheet->getStyle("A" . ($filaSeccionV + 1) . ":L" . ($filaSeccionV + 1))->applyFromArray([
                        'font' => ['bold' => true]
                    ]);
                }

                // Footer
                $sheet->getStyle("L{$totalFilas}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);

                // Altura de filas
                $sheet->getRowDimension(1)->setRowHeight(30);  // Más alto
                $sheet->getRowDimension(2)->setRowHeight(25);  // Más alto
                $sheet->getRowDimension(3)->setRowHeight(25);  // Más alto
                $sheet->getRowDimension(12)->setRowHeight(35); // Encabezados de tabla
                $sheet->getRowDimension(13)->setRowHeight(20);
                $sheet->getRowDimension(14)->setRowHeight(20);
                $sheet->getRowDimension(15)->setRowHeight(20);

                Log::info("TransporteF14Export - Estilos aplicados correctamente");
            }
        ];
    }
}
