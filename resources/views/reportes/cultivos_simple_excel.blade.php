<table>
    <thead>
        <tr>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Subsector</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Grupo</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Subgrupo</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Cultivo</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Código</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cultivos as $c)
            <tr>
                <td>{{ $c->subgrupo->grupo->subsector->descripcion ?? '' }}</td>
                <td>{{ $c->subgrupo->grupo->descripcion ?? '' }}</td>
                <td>{{ $c->subgrupo->descripcion ?? '' }}</td>
                <td>{{ $c->descripcion }}</td>
                <td>{{ $c->codigo }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br><br>
<table>
    <tr>
        <td><b>Fecha de generación:</b> {{ $fecha }}</td>
    </tr>
</table>
