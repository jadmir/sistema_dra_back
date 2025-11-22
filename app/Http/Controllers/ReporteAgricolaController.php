<?php

namespace App\Http\Controllers;

use App\Exports\RegistroAgricolaExport;
use App\Models\AgriRegistro;
use Maatwebsite\Excel\Facades\Excel;

class ReporteAgricolaController extends Controller
{
    public function exportExcel()
    {
        $data = $this->getData();
        return Excel::download(new RegistroAgricolaExport($data), 'registro_agricola.xlsx');
    }

    private function getData()
    {
        $registros = AgriRegistro::with([
            'distrito',
            'detalles.cultivo',
            'detalles.variables.variableCatalogo.unidad'
        ])->orderBy('anio')->get();

        $data = [];

        foreach ($registros as $registro) {
            $anio = $registro->anio ?? 'SIN AÑO';
            $distrito = $registro->distrito->nombre ?? 'SIN DISTRITO';

            foreach ($registro->detalles as $detalle) {
                $cultivo = $detalle->cultivo->nombre ?? 'SIN CULTIVO';

                foreach ($detalle->variables as $variable) {
                    $variableNombre = $variable->variableCatalogo->nombre ?? 'SIN VARIABLE';

                    $data[$distrito][$cultivo][$variableNombre][$anio] = [
                        $variable->ene,
                        $variable->feb,
                        $variable->mar,
                        $variable->abr,
                        $variable->may,
                        $variable->jun,
                        $variable->jul,
                        $variable->ago,
                        $variable->sep,
                        $variable->oct,
                        $variable->nov,
                        $variable->dic,
                        $variable->total_anual,
                    ];
                }
            }
        }

        return $data;
    }
}
