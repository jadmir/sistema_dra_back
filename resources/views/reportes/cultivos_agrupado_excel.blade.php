<table>
    <thead>
        <tr>
            <th>Clasificación</th>
            <th>Descripción</th>
            <th>Código</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subsectores as $sub)
            <tr>
                <td>SUB SECTOR</td>
                <td>{{ $sub->descripcion }}</td>
                <td>{{ $sub->codigo }}</td>
            </tr>

            @foreach($sub->grupos as $g)
                <tr>
                    <td>GRUPO</td>
                    <td>{{ $g->descripcion }}</td>
                    <td>{{ $g->codigo }}</td>
                </tr>

                @foreach($g->subgrupos as $sg)
                    <tr>
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
