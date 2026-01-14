<?php

namespace App\Exports;

use App\Models\AgriEncuesta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EncuestasEstadisticasExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filtros;
    protected $estadisticas;

    public function __construct($filtros = [], $estadisticas = [])
    {
        $this->filtros = $filtros;
        $this->estadisticas = $estadisticas;
    }

    /**
     * Retorna la colección de encuestas según filtros
     */
    public function collection()
    {
        $query = AgriEncuesta::with(['encuestador', 'supervisor'])
            ->orderBy('fecha_recoleccion', 'desc');

        // Aplicar filtros
        if (isset($this->filtros['anio'])) {
            $query->where('anio', $this->filtros['anio']);
        }

        if (isset($this->filtros['mes'])) {
            $query->where('mes', $this->filtros['mes']);
        }

        if (isset($this->filtros['region'])) {
            $query->where('region', $this->filtros['region']);
        }

        if (isset($this->filtros['provincia'])) {
            $query->where('provincia', $this->filtros['provincia']);
        }

        if (isset($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        if (isset($this->filtros['tipo_formulario'])) {
            $query->where('tipo_formulario', $this->filtros['tipo_formulario']);
        }

        if (isset($this->filtros['supervisor_id'])) {
            $query->where('supervisor_id', $this->filtros['supervisor_id']);
        }

        return $query->get();
    }

    /**
     * Encabezados de las columnas
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tipo Formulario',
            'Año',
            'Mes',
            'Fecha Recolección',
            'Región',
            'Provincia',
            'Distrito',
            'Localidad',
            'Estado',
            'Encuestador',
            'Supervisor',
            'Nombre Informante',
            'Teléfono Informante',
            'Fuente Información',
            'Observaciones',
            'Fecha Creación',
            'Fecha Validación',
        ];
    }

    /**
     * Mapeo de datos para cada fila
     */
    public function map($encuesta): array
    {
        return [
            $encuesta->id,
            $encuesta->tipo_formulario,
            $encuesta->anio,
            $encuesta->mes,
            $encuesta->fecha_recoleccion ? \Carbon\Carbon::parse($encuesta->fecha_recoleccion)->format('d/m/Y') : '',
            $encuesta->region,
            $encuesta->provincia,
            $encuesta->distrito,
            $encuesta->localidad,
            $this->getEstadoLabel($encuesta->estado),
            $encuesta->encuestador ? $encuesta->encuestador->nombre_completo : 'N/A',
            $encuesta->supervisor ? $encuesta->supervisor->nombre_completo : 'N/A',
            $encuesta->nombre_informante,
            $encuesta->telefono_informante,
            $encuesta->fuente_informacion,
            $encuesta->observaciones_encuestador,
            $encuesta->created_at ? $encuesta->created_at->format('d/m/Y H:i') : '',
            $encuesta->fecha_validacion ? \Carbon\Carbon::parse($encuesta->fecha_validacion)->format('d/m/Y H:i') : '',
        ];
    }

    /**
     * Estilos de la hoja
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilo del encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Título de la hoja
     */
    public function title(): string
    {
        return 'Estadísticas Encuestas';
    }

    /**
     * Obtener etiqueta de estado
     */
    private function getEstadoLabel($estado): string
    {
        $estados = [
            'borrador' => 'Borrador',
            'enviado' => 'Enviado',
            'validado' => 'Validado',
            'rechazado' => 'Rechazado',
        ];

        return $estados[$estado] ?? $estado;
    }
}
