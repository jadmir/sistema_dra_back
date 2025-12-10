@php
$meses = ['ene','feb','mar','abr','may','jun','jul','ago','set','oct','nov','dic'];
$mesesLabels = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SET','OCT','NOV','DIC'];

// Generar bloques de 12 meses solo dentro del rango exacto
function generarPeriodos12Meses($anioInicio, $mesInicio, $anioFin, $mesFin) {
    $periodos = [];
    $anio = $anioInicio;
    $mes = $mesInicio;

    while ($anio < $anioFin || ($anio == $anioFin && $mes <= $mesFin)) {
        $periodo = [];
        // Crear un bloque de hasta 12 meses, pero sin pasar el rango final
        for ($i = 0; $i < 12 && ($anio < $anioFin || ($anio == $anioFin && $mes <= $mesFin)); $i++) {
            $periodo[] = ['mes' => $mes, 'anio' => $anio];
            $mes++;
            if ($mes > 12) {
                $mes = 1;
                $anio++;
            }
        }
        $periodos[] = $periodo;
    }

    return $periodos;
}

$totalesPeriodos = generarPeriodos12Meses($anioInicio, $mesInicio, $anioFin, $mesFin);
@endphp

<table>
    <thead>
        <tr style="background-color:#2e7d32; color:white; text-align:center;">
            <th>Cultivo</th>
            <th>Variable</th>
            <th>Unidad</th>
            @foreach($totalesPeriodos as $periodo)
                <th colspan="{{ count($periodo)+1 }}">TOTAL {{ $periodo[0]['anio'] }}-{{ $periodo[count($periodo)-1]['anio'] }}</th>
            @endforeach
            <th>TOTAL GENERAL</th>
        </tr>
        <tr style="background-color:#81c784; color:white; text-align:center;">
            <th></th>
            <th></th>
            <th></th>
            @foreach($totalesPeriodos as $periodo)
                @foreach($periodo as $mesInfo)
                    <th>{{ strtoupper($mesesLabels[$mesInfo['mes']-1]) }}</th>
                @endforeach
                <th>Total</th>
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $registro)
            @foreach($registro['variables'] as $variable)
                <tr>
                    <td>{{ $registro['cultivo'] }}</td>
                    <td>{{ $variable['variable'] }}</td>
                    <td>{{ $variable['unidad'] }}</td>

                    @php $totalGeneral = 0; @endphp

                    @foreach($totalesPeriodos as $periodo)
                        @php $totalBloque = 0; @endphp
                        @foreach($periodo as $mesInfo)
                            @php
                                $anio = $mesInfo['anio'];
                                $mes = $mesInfo['mes'];
                                $valor = $variable['meses'][$anio][$mes] ?? 0;
                                $totalBloque += $valor;
                            @endphp
                            <td>{{ $valor }}</td>
                        @endforeach
                        <td>{{ $totalBloque }}</td>
                        @php $totalGeneral += $totalBloque; @endphp
                    @endforeach

                    <td>{{ $totalGeneral }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
