<table>
    <thead>
        <tr>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Clasificación</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Descripción Agrícola</th>
            <th style="background-color:#d0d0d0; border:1px solid #000;">Código</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subsectores as $sub)
            <tr style="background-color:#d7e4bc; font-weight:bold;">
                <td>SUB SECTOR</td>
                <td>{{ $sub->descripcion }}</td>
                <td>{{ $sub->codigo }}</td>
            </tr>

            @foreach($sub->grupos as $g)
                <tr style="background-color:#f2f2f2; font-style:italic;">
                    <td>GRUPO</td>
                    <td>{{ $g->descripcion }}</td>
                    <td>{{ $g->codigo }}</td>
                </tr>

                @foreach($g->subgrupos as $sg)
                    <tr style="background-color:#f9f9f9;">
                        <td>SUB GRUPO</td>
                        <td>{{ $sg->descripcion }}</td>
                        <td>{{ $sg->codigo }}</td>
                    </tr>

                    @foreach($sg->cultivos as $c)
                        <tr>
                            <td>CULTIVO</td>
                            <td>{{ $c->descripcion }}</td>
                            <td>{{ $c->codigo }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>

<br><br>
<table>
    <tr>
        <td><b>Fecha de generación:</b> {{ $fecha }}</td>
    </tr>
</table>
