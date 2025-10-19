<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Cultivos</title>
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
table { width: 100%; border-collapse: collapse; margin-top: 5px; }
th, td { border: 1px solid #000; padding: 4px; }
th { background: #f0f0f0; text-align: center; }
</style>
</head>
<body>

@include('reportes.header')

<table>
<thead>
<tr>
    <th>Subsector</th>
    <th>Grupo</th>
    <th>Subgrupo</th>
    <th>Cultivo</th>
    <th>Código</th>
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
</body>
</html>
