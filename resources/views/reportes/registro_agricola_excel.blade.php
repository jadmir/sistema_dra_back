<table border="1" cellspacing="0" cellpadding="3">
    <thead>
        <tr>
            <th rowspan="2">CULTIVO</th>
            <th rowspan="2">VARIABLES</th>
            @php
                // Detectar todos los años únicos
                $allYears = collect($data)->flatMap(fn($cultivos) =>
                    collect($cultivos)->flatMap(fn($variables) =>
                        collect($variables)->flatMap(fn($vars) => array_keys($vars))
                    )
                )->unique()->sort()->toArray();
            @endphp

            {{-- Encabezado superior con los años --}}
            @foreach($allYears as $anio)
                <th colspan="13">{{ $anio }}</th>
            @endforeach
        </tr>
        <tr>
            {{-- Encabezado de meses --}}
            @foreach($allYears as $anio)
                @foreach(['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC','TOTAL'] as $mes)
                    <th>{{ $mes }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($data as $distrito => $cultivos)
        {{-- 🔹 Fila con nombre del distrito --}}
        <tr>
            <td colspan="{{ 2 + (13 * count($allYears)) }}" style="font-weight:bold; background:#f2f2f2;">
                DISTRITO: {{ strtoupper($distrito) }}
            </td>
        </tr>

        {{-- 🔹 Encabezado por cada distrito --}}
        <tr>
            <th rowspan="2" style="text-align:center;">CULTIVO</th>
            <th rowspan="2" style="text-align:center;">VARIABLE</th>

            {{-- Encabezado de años --}}
            @foreach($allYears as $anio)
                <th colspan="13" style="text-align:center;">{{ $anio }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($allYears as $anio)
                <th>Ene</th><th>Feb</th><th>Mar</th><th>Abr</th><th>May</th><th>Jun</th>
                <th>Jul</th><th>Ago</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dic</th><th>Total</th>
            @endforeach
        </tr>

        {{-- 🔹 Filas de cultivos y variables --}}
        @foreach($cultivos as $cultivo => $variables)
            @foreach($variables as $index => $var)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ count($variables) }}">{{ strtoupper($cultivo) }}</td>
                    @endif

                    {{-- Nombre de variable --}}
                    <td>{{ $var['nombre'] }}</td>

                    {{-- Valores por año --}}
                    @foreach($allYears as $anio)
                        @php
                            $valores = $var['anios'][$anio] ?? array_fill(0, 13, '');
                        @endphp
                        @foreach($valores as $v)
                            <td>{{ $v }}</td>
                        @endforeach
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    @endforeach

    </tbody>
</table>
