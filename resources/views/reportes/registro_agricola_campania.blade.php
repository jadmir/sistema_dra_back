@php
    $mesesText = [
        1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
        7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
    ];

    $mesesRango = [];
    for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
        $inicioMes = ($anio === $anioInicio) ? $mesInicio : 1;
        $finMes    = ($anio === $anioFin) ? $mesFin : 12;
        for ($m = $inicioMes; $m <= $finMes; $m++) {
            $mesesRango[] = ['anio' => $anio, 'mes' => $m];
        }
    }

    $productos = [];
    $totalGeneralMeses = [];
    $totalGeneralCampania = 0;

    foreach ($mesesRango as $item) {
        $key = $item['anio'] . '-' . $item['mes'];
        $totalGeneralMeses[$key] = 0;
    }
@endphp

<style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #4CAF50; text-align: center; padding: 4px; font-size: 12px; }
    .thead-title { font-size: 16px; font-weight: bold; background: #dff0d8; }
    .thead-sub { font-size: 14px; font-weight: bold; background: #e6f2e6; }
    .year-header { background: #cce5cc; font-weight: bold; border-bottom: 2px solid #4CAF50; }
    .month-header { background: #e6f2e6; font-weight: bold; }
    .total-row { background: #d9f2d9; font-weight: bold; border-top: 3px double #4CAF50; }
    .number { text-align: right; }
</style>

<table>
    <tr><th colspan="{{ 3 + count($mesesRango) }}" class="thead-title">DIRECCIÓN REGIONAL DE AGRICULTURA JUNÍN</th></tr>
    <tr><th colspan="{{ 3 + count($mesesRango) }}" class="thead-title">DIRECCIÓN DE ESTADÍSTICA E INFORMACIÓN AGRARIA</th></tr>
    <tr><th colspan="{{ 3 + count($mesesRango) }}" class="thead-sub">INTENCIONES DE SIEMBRA CAMPAÑA AGRÍCOLA {{ $anioInicio }} - {{ $anioFin }}</th></tr>
    <tr><th colspan="{{ 3 + count($mesesRango) }}" class="thead-sub">REGIÓN: {{ $region }} — PROVINCIA: {{ $provincia }} — DISTRITO: {{ $distrito }}</th></tr>

    <tr>
        <th rowspan="2">Producto</th>
        @php
            $groupedByYear = [];
            foreach ($mesesRango as $item) {
                $groupedByYear[$item['anio']][] = $item['mes'];
            }
        @endphp
        @foreach ($groupedByYear as $year => $months)
            <th colspan="{{ count($months) }}" class="year-header">{{ $year }}</th>
        @endforeach
        <th rowspan="2">TOTAL</th>
    </tr>

    <tr>
        @foreach ($mesesRango as $item)
            <th class="month-header">{{ strtoupper($mesesText[$item['mes']]) }}</th>
        @endforeach
    </tr>

    @foreach ($registros ?? [] as $registro)
        @foreach ($registro->detalles ?? [] as $detalle)
            @foreach ($detalle->variablesFiltradas ?? collect() as $var)
                @php
                    $producto = $detalle->cultivo->nombre ?? 'SIN CULTIVO';

                    if (!isset($productos[$producto])) {
                        $productos[$producto] = [
                            'meses' => [],
                            'total' => 0,
                        ];
                    }

                    foreach ($mesesRango as $item) {
                        $key = $item['anio'] . '-' . $item['mes'];
                        $productos[$producto]['meses'][$key] = $productos[$producto]['meses'][$key] ?? 0;
                        $totalGeneralMeses[$key] = $totalGeneralMeses[$key] ?? 0;
                    }

                    foreach ($mesesRango as $item) {
                        $key = $item['anio'] . '-' . $item['mes'];
                        if ($registro->anio == $item['anio']) {
                            $col = strtolower($mesesText[$item['mes']]);
                            $valor = floatval($var->$col ?? 0);

                            $productos[$producto]['meses'][$key] += $valor;
                            $totalGeneralMeses[$key] += $valor;
                        }
                    }

                    $productos[$producto]['total'] += $var->total_campania ?? 0;
                    $totalGeneralCampania += $var->total_campania ?? 0;
                @endphp
            @endforeach
        @endforeach
    @endforeach

    @foreach ($productos as $nombre => $info)
        <tr>
            <td>{{ $nombre }}</td>
            @foreach ($mesesRango as $item)
                @php $key = $item['anio'] . '-' . $item['mes']; @endphp
                <td class="number">{{ number_format($info['meses'][$key] ?? 0, 2, '.', '') }}</td>
            @endforeach
            <td class="number">{{ number_format($info['total'], 2, '.', '') }}</td>
        </tr>
    @endforeach

    <tr class="total-row">
        <td>TOTAL GENERAL</td>
        @foreach ($mesesRango as $item)
            @php $key = $item['anio'] . '-' . $item['mes']; @endphp
            <td class="number">{{ number_format($totalGeneralMeses[$key] ?? 0, 2, '.', '') }}</td>
        @endforeach
        <td class="number">{{ number_format($totalGeneralCampania, 2, '.', '') }}</td>
    </tr>
</table>
