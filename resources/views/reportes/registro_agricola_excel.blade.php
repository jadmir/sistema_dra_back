<table>
    <tr>
        <td colspan="20" style="font-size:16px; font-weight:bold;">
            REGISTRO AGRÍCOLA – Año {{ $registro->anio }}
        </td>
    </tr>

    <tr><td colspan="20"></td></tr>

    <tr>
        <td><strong>Región:</strong></td>
        <td>{{ $registro->region->nombre ?? '-' }}</td>

        <td><strong>Provincia:</strong></td>
        <td>{{ $registro->provincia->nombre ?? '-' }}</td>

        <td><strong>Distrito:</strong></td>
        <td>{{ $registro->distrito->nombre ?? '-' }}</td>
    </tr>

    <tr><td colspan="20"></td></tr>

    <tr>
        <th>Cultivo</th>
        <th>Variable</th>
        @foreach (['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'] as $m)
            <th>{{ $m }}</th>
        @endforeach
        <th>Total</th>
        <th>Unidad</th>
    </tr>

    @foreach ($registro->detalles as $detalle)
        @php
            $variables = $detalle->variables;
            $rowspan = count($variables);
            $first = true;
        @endphp

        @foreach ($variables as $var)
            <tr>
                @if($first)
                    <td rowspan="{{ $rowspan }}">{{ $detalle->cultivo->nombre }}</td>
                    @php $first = false; @endphp
                @endif

                <td>{{ $var->variableCatalogo->nombre }}</td>
                <td>{{ $var->ene }}</td>
                <td>{{ $var->feb }}</td>
                <td>{{ $var->mar }}</td>
                <td>{{ $var->abr }}</td>
                <td>{{ $var->may }}</td>
                <td>{{ $var->jun }}</td>
                <td>{{ $var->jul }}</td>
                <td>{{ $var->ago }}</td>
                <td>{{ $var->sep }}</td>
                <td>{{ $var->oct }}</td>
                <td>{{ $var->nov }}</td>
                <td>{{ $var->dic }}</td>

                <td>{{ $var->total_anual }}</td>
                <td>{{ $var->variableCatalogo->unidad->nombre ?? '-' }}</td>
            </tr>
        @endforeach
    @endforeach
</table>
