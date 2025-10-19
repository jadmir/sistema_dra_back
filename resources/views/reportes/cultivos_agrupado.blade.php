<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Agrupado</title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-top: 5px; }
th, td { border: 1px solid #000; padding: 4px; }
th { background: #f0f0f0; text-align: center; }
tr.subsector td { background: #d7e4bc; font-weight: bold; }
tr.grupo td { background: #f2f2f2; font-style: italic; }
tr.subgrupo td { background: #f9f9f9; }
</style>
</head>
<body>

@include('reportes.header')

<table>
    <tr>
        <td colspan="5" style="text-align:center;"><strong>REPORTE DE CULTIVOS (AGRUPADO)</strong></td>
    </tr>
    <tr>
        <td colspan="5">Generado: {{ $fecha ?? now()->format('d/m/Y H:i') }}</td>
    </tr>
    <tr><td colspan="5"></td></tr>

    <thead>
        <tr>
            <th>Subsector (Cód - Desc)</th>
            <th>Grupo (Cód - Desc)</th>
            <th>Subgrupo (Cód - Desc)</th>
            <th>Código cultivo</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
    @php $totalGeneral = 0; @endphp

    @forelse($subsectores as $ss)
        @php $totalSubsector = 0; @endphp

        @foreach($ss->grupos as $g)
            @php $totalGrupo = 0; @endphp

            @foreach($g->subgrupos as $sg)
                @php
                    $countCultivos = $sg->cultivos->count();
                    $totalGrupo += $countCultivos;
                    $totalSubsector += $countCultivos;
                    $totalGeneral += $countCultivos;
                @endphp

                @if($countCultivos > 0)
                    @foreach($sg->cultivos as $c)
                        <tr>
                            <td>{{ $ss->codigo }}@if(!empty($ss->descripcion)) - {{ $ss->descripcion }}@endif</td>
                            <td>{{ $g->codigo }}@if(!empty($g->descripcion)) - {{ $g->descripcion }}@endif</td>
                            <td>{{ $sg->codigo }}@if(!empty($sg->descripcion)) - {{ $sg->descripcion }}@endif</td>
                            <td>{{ $c->codigo }}</td>
                            <td>{{ $c->descripcion }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $ss->codigo }}@if(!empty($ss->descripcion)) - {{ $ss->descripcion }}@endif</td>
                        <td>{{ $g->codigo }}@if(!empty($g->descripcion)) - {{ $g->descripcion }}@endif</td>
                        <td>{{ $sg->codigo }}@if(!empty($sg->descripcion)) - {{ $sg->descripcion }}@endif</td>
                        <td colspan="2">Sin cultivos</td>
                    </tr>
                @endif
            @endforeach

            <!-- Total por Grupo -->
            <tr>
                <td colspan="5">
                    <strong>Total Grupo {{ $g->codigo }}@if(!empty($g->descripcion)) - {{ $g->descripcion }}@endif: {{ $totalGrupo }}</strong>
                </td>
            </tr>
        @endforeach

        <!-- Total por Subsector -->
        <tr>
            <td colspan="5">
                <strong>Total Subsector {{ $ss->codigo }}@if(!empty($ss->descripcion)) - {{ $ss->descripcion }}@endif: {{ $totalSubsector }}</strong>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">Sin resultados</td>
        </tr>
    @endforelse

    <!-- Total general -->
    <tr>
        <td colspan="5" style="text-align:right;"><strong>Total general: {{ $totalGeneral }}</strong></td>
    </tr>
    </tbody>
</table>
</body>
</html>
