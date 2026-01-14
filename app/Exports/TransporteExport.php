<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Support\Facades\Log;

class TransporteExport implements FromArray
{
    protected $dentro_region;
    protected $fuera_region;
    protected $estadisticas;
    protected $filtros;

    public function __construct($dentro_region, $fuera_region, $estadisticas = [], $filtros = [])
    {
        $this->dentro_region = $dentro_region;
        $this->fuera_region = $fuera_region;
        $this->estadisticas = $estadisticas;
        $this->filtros = $filtros;
    }

    public function array(): array
    {
        // Log para ver los datos del filtro
        Log::info("TransporteExport - Datos de filtros:", [
            'encuestador_nombre' => $this->filtros['encuestador_nombre'] ?? 'NO EXISTE',
            'encuestador_apellido' => $this->filtros['encuestador_apellido'] ?? 'NO EXISTE',
            'supervisor_nombre' => $this->filtros['supervisor_nombre'] ?? 'NO EXISTE',
            'supervisor_apellido' => $this->filtros['supervisor_apellido'] ?? 'NO EXISTE',
        ]);

        $data = [];

        // ENCABEZADO (Filas 1-3)
        $data[] = ['', '', 'DIRECCIÓN GENERAL DE ESTADÍSTICA, SEGUIMIENTO Y EVALUACIÓN DE POLÍTICAS-DGESEP', '', '', '', '', '', '', '', '', 'F-14'];
        $data[] = ['', '', 'DIRECCIÓN DE ESTADÍSTICA E INFORMACIÓN AGRARIA - DEIA', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', 'PRECIOS (S/.) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS', '', '', '', '', '', '', '', '', ''];

        // INFORMACIÓN (Filas 4-8)
        $data[] = ['Sistema:SIEA - Sistema Integrado de Estadística Agraria', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['I. UBICACIÓN POLÍTICA', '', '', 'Provincia:' . $this->filtros['provincia'], '', '', 'Distrito:' . $this->filtros['distrito'], '', '', '', '', ''];
        $data[] = ['Región:' . $this->filtros['region'], '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['II. AÑO Y MES DE REFERENCIA', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['Año:' . $this->filtros['anio'], '', '', 'Mes:' . $this->filtros['mes_nombre'], '', '', '', '', '', '', '', ''];

        // TABLA (Filas 9-11)
        $data[] = ['', '', 'III. INFORMACIÓN DE PRECIOS DE TRANSPORTE DE PRODUCTOS', '', '', '', '', '', '', '', '', ''];
        $data[] = ['Tipo', 'Origen', 'Destino', 'Vía', 'Producto', '', 'Unidad de medida', 'Precio con IGV (S/. x UM) -', '', '', '', 'N° REG.'];
        $data[] = ['', '', '', '', '', '', '', 'Mínimo', 'Máximo', '', '', ''];

        // DENTRO DE LA REGIÓN (Fila 12+)
        $data[] = ['Dentro de la región:', '', '', '', '', '', '', '', '', '', '', ''];
        foreach ($this->dentro_region as $ruta) {
            $data[] = [
                $ruta['tipo'],
                $ruta['origen'],
                $ruta['destino'],
                $ruta['via'],
                $ruta['producto'],
                '',
                $ruta['unidad_medida'],
                number_format($ruta['precio_minimo'], 2),
                number_format($ruta['precio_maximo'], 2),
                '',
                '',
                $ruta['num_registros']
            ];
        }

        // FUERA DE LA REGIÓN
        if ($this->fuera_region->isNotEmpty()) {
            $data[] = ['Fuera de la región:', '', '', '', '', '', '', '', '', '', '', ''];
            foreach ($this->fuera_region as $ruta) {
                $data[] = [
                    $ruta['tipo'],
                    $ruta['origen'],
                    $ruta['destino'],
                    $ruta['via'],
                    $ruta['producto'],
                    '',
                    $ruta['unidad_medida'],
                    number_format($ruta['precio_minimo'], 2),
                    number_format($ruta['precio_maximo'], 2),
                    '',
                    '',
                    $ruta['num_registros']
                ];
            }
        }

        // IV. OBSERVACIONES
        $data[] = ['IV. OBSERVACIONES', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = [
            '- Total de rutas registradas: ' . $this->estadisticas['rutas_distintas'] . "\n" .
            '- Costo promedio general: S/. ' . number_format($this->estadisticas['precio_promedio'], 2),
            '', '', '', '', '', '', '', '', '', '', ''
        ];

        // Fecha y footer
        $data[] = ['Fecha de generación:' . now()->format('d/m/Y H:i'), '', '', '', 'Período:01/11/2025 - 30/11/2025', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', 'F7-EISA-DGESEP-DEIA'];

        // SEPARADOR
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', ''];

        // V. ENCUESTADOR Y SUPERVISOR
        $data[] = ['V. Del encuestador y supervisor', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['ENCUESTADOR', '', '', '', '', '', 'SUPERVISOR', '', '', '', '', ''];
        $data[] = [
            'Nombres:',
            $this->filtros['encuestador_nombre'] ?? '',
            '',
            '',
            '',
            '',
            'Nombres:',
            $this->filtros['supervisor_nombre'] ?? '',
            '',
            '',
            '',
            ''
        ];
        $data[] = [
            'Apellidos:',
            $this->filtros['encuestador_apellido'] ?? '',
            '',
            '',
            '',
            '',
            'Apellidos:',
            $this->filtros['supervisor_apellido'] ?? '',
            '',
            '',
            '',
            ''
        ];
        $data[] = [
            'Cargo:',
            $this->filtros['encuestador_cargo'] ?? '',
            '',
            '',
            '',
            '',
            'Cargo:',
            $this->filtros['supervisor_cargo'] ?? '',
            '',
            '',
            '',
            ''
        ];
        $data[] = [
            '',
            '',
            '',
            '',
            '',
            '',
            'Fecha de supervisión:',
            !empty($this->filtros['fecha_validacion']) ? \Carbon\Carbon::parse($this->filtros['fecha_validacion'])->format('d/m/Y') : '',
            '',
            '',
            '',
            ''
        ];
        $data[] = [
            '',
            '',
            '',
            '',
            '',
            '',
            'Firma:',
            '_______________________________',
            '',
            '',
            '',
            ''
        ];

        Log::info("TransporteExport - Total de filas en array(): " . count($data));

        return $data;
    }
}
