<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgroquimicosExportArray implements FromArray, WithStyles, WithEvents, WithColumnWidths
{
    protected $datos;
    protected $estadisticas;
    protected $filtros;

    public function __construct($datos, $estadisticas = [], $filtros = [])
    {
        $this->datos = $datos;
        $this->estadisticas = $estadisticas;
        $this->filtros = $filtros;
    }

    public function array(): array
    {
        $filas = [];

        // ============================================================
        // ENCABEZADO (similar al PDF)
        // ============================================================
        $filas[] = ['¡SIEA', '', 'Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP', '', '', '', '', '', '', '', '', '', '', '', 'F-6', ''];
        $filas[] = ['Sistema Integrado de', '', 'Dirección de Estadística e Información Agraria - DEIA', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Estadística Agraria', '', 'PRECIOS DE PRINCIPALES AGROQUÍMICOS (S/.)', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // I. UBICACIÓN POLÍTICA y II. AÑO Y MES
        // ============================================================
        $region = $this->filtros['region'] ?? 'ICA';
        $provincia = $this->filtros['provincia'] ?? 'ICA';
        $distrito = $this->filtros['distrito'] ?? 'LOS AQUIJES';
        $anio = $this->filtros['anio'] ?? date('Y');
        $mes = $this->filtros['mes'] ?? date('m');

        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $mesNombre = $meses[(int)$mes] ?? 'Noviembre';

        $filas[] = ['I. UBICACIÓN POLÍTICA', '', '', '', 'II. AÑO Y MES', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Región:', $region, '', '', 'Año:', $anio, '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Provincia:', $provincia, '', '', 'Mes:', $mes . ' - ' . $mesNombre, '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Distrito:', $distrito, '', '', 'Fecha generación:', now()->format('d/m/Y H:i'), '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // RESUMEN ESTADÍSTICO DEL PERÍODO
        // ============================================================
        $totalEncuestas = $this->estadisticas['total_encuestas'] ?? 0;
        $totalRegistros = $this->estadisticas['total_registros'] ?? 0;
        $productosDistintos = $this->estadisticas['productos_distintos'] ?? 0;
        $precioPromedio = $this->estadisticas['precio_promedio'] ?? 0;

        $filas[] = ['RESUMEN ESTADÍSTICO DEL PERÍODO', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Total Encuestas', 'Total Registros', 'Productos Distintos', 'Precio Promedio', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = [$totalEncuestas, $totalRegistros, $productosDistintos, 'S/. ' . number_format($precioPromedio, 2), '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // III. INFORMACIÓN DE PRECIOS DE PRINCIPALES AGROQUÍMICOS
        // ============================================================
        $filas[] = ['III. INFORMACIÓN DE PRECIOS DE PRINCIPALES AGROQUÍMICOS', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Encabezados de tabla (completos)
        $filas[] = ['TIPO', 'AGROQUÍMICO', 'ENVASE', 'MÍNIMO', 'PROMEDIO', 'MÁXIMO', 'P. MAX FUERA', 'P. MIN FUERA', 'P. MAX DENTRO', 'P. MIN DENTRO', 'N° REG.', 'VIGENCIA', '', '', '', ''];

        // ============================================================
        // DATOS POR CATEGORÍA
        // ============================================================
        // Los datos ya vienen como array simple desde el controlador
        $datosArray = $this->datos;

        // Mapear categorías
        $categoriasMap = [
            'acaricida' => 'ACARICIDAS',
            'adherente' => 'ADHERENTES',
            'fungicida' => 'FUNGICIDAS',
            'Fungicidas' => 'FUNGICIDAS',
            'Herbicidas' => 'HERBICIDAS',
            'Insecticidas' => 'INSECTICIDAS',
            'nutriente foliar' => 'NUTRIENTES FOLIARES',
            'regulador de crecimiento' => 'REGULADORES DE CRECIMIENTO',
            'OTROS' => 'OTROS'
        ];

        // Agrupar por categoría
        $agrupadoPorCategoria = collect($datosArray)->groupBy(function($item) use ($categoriasMap) {
            $cat = $item['categoria'] ?? 'OTROS';
            return $categoriasMap[$cat] ?? 'OTROS';
        });

        // Orden de categorías
        $ordenCategorias = [
            'ACARICIDAS',
            'ADHERENTES',
            'FUNGICIDAS',
            'HERBICIDAS',
            'INSECTICIDAS',
            'NUTRIENTES FOLIARES',
            'REGULADORES DE CRECIMIENTO',
            'OTROS'
        ];

        $totalGeneralRegistros = 0;

        foreach ($ordenCategorias as $nombreCategoria) {
            $productos = $agrupadoPorCategoria->get($nombreCategoria, collect());

            if ($productos->isEmpty()) {
                continue;
            }

            // Fila de categoría
            $filas[] = [$nombreCategoria, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

            // Productos de la categoría
            foreach ($productos as $producto) {
                $filas[] = [
                    $nombreCategoria,
                    $producto['nombre'] ?? '',
                    $producto['envase'] ?? '1 lt',
                    'S/. ' . number_format($producto['precio_minimo'] ?? 0, 2),
                    'S/. ' . number_format($producto['precio_promedio'] ?? 0, 2),
                    'S/. ' . number_format($producto['precio_maximo'] ?? 0, 2),
                    'S/. ' . number_format($producto['precio_maximo'] ?? 0, 2), // P. MAX FUERA
                    'S/. ' . number_format($producto['precio_minimo'] ?? 0, 2), // P. MIN FUERA
                    'S/. ' . number_format($producto['precio_maximo'] ?? 0, 2), // P. MAX DENTRO
                    'S/. ' . number_format($producto['precio_minimo'] ?? 0, 2), // P. MIN DENTRO
                    $producto['num_registros'] ?? 0,
                    'Actual', // VIGENCIA
                    '',
                    '',
                    '',
                    ''
                ];
                $totalGeneralRegistros += $producto['num_registros'] ?? 0;
            }
        }

        // TOTALES
        $filas[] = ['', '', '', '', '', 'PRECIO PROMEDIO GENERAL:', 'S/. ' . number_format($precioPromedio, 2), $totalGeneralRegistros, '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // Notas al pie (igual al PDF)
        $filas[] = ['* Productos no registrados en SENASA', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['** Los precios promedio son calculados a partir de múltiples encuestas realizadas en el período indicado', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // IV. OBSERVACIONES
        // ============================================================
        $totalProductos = $this->estadisticas['productos_distintos'] ?? 0;
        $precioPromedio = $this->estadisticas['precio_promedio'] ?? 0;

        // Calcular mínimo y máximo de todos los productos
        $precioMinimo = PHP_INT_MAX;
        $precioMaximo = 0;

        foreach ($datosArray as $producto) {
            $min = floatval($producto['precio_minimo'] ?? 0);
            $max = floatval($producto['precio_maximo'] ?? 0);

            if ($min > 0 && $min < $precioMinimo) {
                $precioMinimo = $min;
            }
            if ($max > $precioMaximo) {
                $precioMaximo = $max;
            }
        }

        if ($precioMinimo === PHP_INT_MAX) {
            $precioMinimo = 0;
        }

        $filas[] = ['IV. OBSERVACIONES', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['- Total de productos registrados: ' . $totalProductos, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['- Precio promedio general: S/. ' . number_format($precioPromedio, 2), '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['- Rango de precios: S/. ' . number_format($precioMinimo, 2) . ' - S/. ' . number_format($precioMaximo, 2), '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // INFORMACIÓN DEL ENCUESTADOR
        // ============================================================
        $encuestador_nombre = trim(($this->filtros['encuestador_nombre'] ?? '') . ' ' . ($this->filtros['encuestador_apellido'] ?? ''));
        $encuestador_cargo = $this->filtros['encuestador_cargo'] ?? '';

        $filas[] = ['ENCUESTADOR', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Nombre y Apellido:', !empty($encuestador_nombre) ? $encuestador_nombre : '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Cargo:', !empty($encuestador_cargo) ? $encuestador_cargo : '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // INFORMACIÓN DEL SUPERVISOR
        // ============================================================
        $supervisor_nombre = trim(($this->filtros['supervisor_nombre'] ?? '') . ' ' . ($this->filtros['supervisor_apellido'] ?? ''));
        $supervisor_cargo = $this->filtros['supervisor_cargo'] ?? '';
        $fecha_supervision = !empty($this->filtros['fecha_validacion']) ? date('d/m/Y', strtotime($this->filtros['fecha_validacion'])) : '';

        $filas[] = ['SUPERVISOR', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Nombre y Apellido:', !empty($supervisor_nombre) ? $supervisor_nombre : '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Cargo:', !empty($supervisor_cargo) ? $supervisor_cargo : '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['Fecha de Supervisión:', !empty($fecha_supervision) ? $fecha_supervision : '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // ============================================================
        // FIRMA
        // ============================================================
        $filas[] = ['Firma:', '________________________', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        $filas[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Footer
        $filas[] = ['SISTEMA DE INFORMACIÓN ESTADÍSTICA AGRARIA - SIEA | F6-EISA-DGESEP-DEIA', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        return $filas;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // TIPO
            'B' => 40,  // AGROQUÍMICO
            'C' => 12,  // ENVASE
            'D' => 14,  // MÍNIMO
            'E' => 14,  // PROMEDIO
            'F' => 14,  // MÁXIMO
            'G' => 18,  // PRECIO PROMEDIO
            'H' => 12,  // N° REG
            'I' => 5,
            'J' => 5,
            'K' => 5,
            'L' => 5,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Encabezado amarillo (filas 1-3)
            '1:3' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '8B4513']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '8B4513']]],
            ],
            // Badge F-6
            'L1:L3' => [
                'font' => ['bold' => true, 'size' => 24, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '8B4513']]],
            ],
            // Sección de ubicación política y año/mes (filas 5-8)
            '5:8' => [
                'font' => ['size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFDE7']],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
            ],
            // Título resumen estadístico (fila 10)
            '10' => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '8B4513']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '8B4513']]],
            ],
            // Encabezados de estadísticas (fila 11)
            '11' => [
                'font' => ['bold' => false, 'size' => 9, 'color' => ['rgb' => '666666']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
            ],
            // Valores de estadísticas (fila 12)
            '12' => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
            ],
            // Título de tabla (fila 14)
            '14' => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '8B4513']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '8B4513']]],
            ],
            // Encabezados de tabla de datos (fila 15)
            '15' => [
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE082']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // ============================================================
                // MERGE DE CELDAS
                // ============================================================
                // Encabezado (filas 1-3)
                $sheet->mergeCells('A1:B1');  // ¡SIEA
                $sheet->mergeCells('C1:K1');  // Dirección General...
                $sheet->mergeCells('A2:B2');  // Sistema Integrado de
                $sheet->mergeCells('C2:K2');  // Dirección de Estadística...
                $sheet->mergeCells('A3:B3');  // Estadística Agraria
                $sheet->mergeCells('C3:K3');  // PRECIOS DE PRINCIPALES...

                // Secciones de información (filas 5-8)
                $sheet->mergeCells('A5:D5');  // I. UBICACIÓN POLÍTICA
                $sheet->mergeCells('E5:K5');  // II. AÑO Y MES

                // Resumen estadístico (filas 10-12)
                $sheet->mergeCells('A10:K10');  // RESUMEN ESTADÍSTICO DEL PERÍODO

                // Título de tabla (fila 14)
                $sheet->mergeCells('A14:K14');  // III. INFORMACIÓN DE PRECIOS...

                // Encabezados de tabla con rowspan (fila 15)
                // TIPO, AGROQUÍMICO, ENVASE tienen rowspan
                // PRECIOS (S/.) tiene colspan 3
                // PRECIO PROMEDIO, N° REG tienen rowspan

                // ============================================================
                // ALTURA DE FILAS
                // ============================================================
                $sheet->getRowDimension(1)->setRowHeight(22);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(20);
                $sheet->getRowDimension(10)->setRowHeight(22);
                $sheet->getRowDimension(12)->setRowHeight(30);
                $sheet->getRowDimension(14)->setRowHeight(22);
                $sheet->getRowDimension(15)->setRowHeight(35);

                // ============================================================
                // ESTILOS PARA LAS CATEGORÍAS Y PRODUCTOS
                // ============================================================
                $categorias = [
                    'ACARICIDAS',
                    'ADHERENTES',
                    'FUNGICIDAS',
                    'HERBICIDAS',
                    'INSECTICIDAS',
                    'NUTRIENTES FOLIARES',
                    'REGULADORES DE CRECIMIENTO',
                    'OTROS'
                ];

                for ($row = 16; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    $cellValueB = $sheet->getCell('B' . $row)->getValue();

                    // Fila de categoría (solo tiene valor en columna A, B está vacía)
                    if (!empty($cellValue) && in_array($cellValue, $categorias) && empty($cellValueB)) {
                        $sheet->mergeCells('A' . $row . ':H' . $row);
                        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3E0']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                            'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '8B4513']]],
                        ]);
                    }
                    // Fila de producto (tiene valor en columna B)
                    elseif (!empty($cellValueB)) {
                        // Aplicar bordes a toda la fila de datos
                        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);

                        // Alineación
                        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                        $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('D' . $row . ':H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                        // Estilo para precio promedio (columna G) - destacado
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
                        ]);
                    }
                    // Fila de totales
                    elseif (strpos($cellValue, 'PRECIO PROMEDIO GENERAL') !== false || strpos($sheet->getCell('F' . $row)->getValue(), 'PRECIO PROMEDIO GENERAL') !== false) {
                        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 10],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFE082']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);
                        $sheet->getStyle('G' . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
                            'font' => ['bold' => true, 'size' => 11],
                        ]);
                        $sheet->getStyle('H' . $row)->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF9C4']],
                            'font' => ['bold' => true, 'size' => 11],
                        ]);
                    }
                }

                // ============================================================
                // ESTILOS ESPECIALES
                // ============================================================
                // Badge F-6 (filas 1-3, columna L)
                $sheet->getStyle('L1:L3')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 28, 'color' => ['rgb' => '8B4513']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '8B4513']]],
                ]);

                // Etiquetas en negrita (columnas A en sección de información)
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('A6')->getFont()->setBold(true);
                $sheet->getStyle('A7')->getFont()->setBold(true);
                $sheet->getStyle('A8')->getFont()->setBold(true);
                $sheet->getStyle('E5')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('E6')->getFont()->setBold(true);
                $sheet->getStyle('E7')->getFont()->setBold(true);
                $sheet->getStyle('E8')->getFont()->setBold(true);

                // Colores para las estadísticas (fila 12)
                $sheet->getStyle('A12')->getFont()->getColor()->setRGB('2196F3'); // Azul
                $sheet->getStyle('B12')->getFont()->getColor()->setRGB('4CAF50'); // Verde
                $sheet->getStyle('C12')->getFont()->getColor()->setRGB('FF9800'); // Naranja
                $sheet->getStyle('D12')->getFont()->getColor()->setRGB('9C27B0'); // Morado

                // ============================================================
                // ESTILOS PARA IV. OBSERVACIONES, ENCUESTADOR Y SUPERVISOR
                // ============================================================
                // Buscar las filas dinámicamente
                for ($row = 16; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();

                    // IV. OBSERVACIONES
                    if (strpos($cellValue, 'IV. OBSERVACIONES') !== false) {
                        $sheet->mergeCells('A' . $row . ':K' . $row);
                        $sheet->getStyle('A' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '8B4513']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC107']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '8B4513']]],
                        ]);

                        // Área de observaciones (3 filas siguientes)
                        for ($i = 1; $i <= 3; $i++) {
                            $sheet->mergeCells('A' . ($row + $i) . ':K' . ($row + $i));
                            $sheet->getStyle('A' . ($row + $i))->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFDE7']],
                                'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
                                'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                            ]);
                            $sheet->getRowDimension($row + $i)->setRowHeight(20);
                        }
                    }

                    // ENCUESTADOR
                    if ($cellValue === 'ENCUESTADOR') {
                        $sheet->mergeCells('A' . $row . ':K' . $row);
                        $sheet->getStyle('A' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '8B4513']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3E0']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
                        ]);
                    }

                    // SUPERVISOR
                    if ($cellValue === 'SUPERVISOR') {
                        $sheet->mergeCells('A' . $row . ':K' . $row);
                        $sheet->getStyle('A' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '8B4513']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3E0']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                            'borders' => ['outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFC107']]],
                        ]);
                    }

                    // Etiquetas: Nombre y Apellido, Cargo, Fecha de Supervisión
                    if (in_array($cellValue, ['Nombre y Apellido:', 'Cargo:', 'Fecha de Supervisión:'])) {
                        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(10);
                        $sheet->mergeCells('B' . $row . ':K' . $row);
                        $sheet->getStyle('B' . $row)->applyFromArray([
                            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        ]);
                    }

                    // Firma
                    if ($cellValue === 'Firma:') {
                        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(10);
                        $sheet->mergeCells('B' . $row . ':K' . $row);
                        $sheet->getStyle('B' . $row)->applyFromArray([
                            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(40);
                    }
                }

                // Footer
                $lastRow = $highestRow;
                $sheet->mergeCells('A' . $lastRow . ':K' . $lastRow);
                $sheet->getStyle('A' . $lastRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '8B4513']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                ]);
            }
        ];
    }
}
