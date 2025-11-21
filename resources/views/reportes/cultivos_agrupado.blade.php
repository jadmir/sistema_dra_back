<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Cultivos Agrícolas</title>
<style>
body { 
    font-family: DejaVu Sans, sans-serif; 
    font-size: 10px; 
    color: #2d4a2b; 
    margin: 20px;
}
.header-table {
    width: 100%;
    border: 2px solid #8b7355;
    margin-bottom: 10px;
    border-collapse: collapse;
}
.header-table td {
    padding: 10px;
    border: 1px solid #8b7355;
}
.logo-cell {
    width: 15%;
    text-align: center;
    vertical-align: middle;
}
.title-cell {
    width: 70%;
    text-align: center;
    vertical-align: middle;
}
.date-cell {
    width: 15%;
    text-align: right;
    vertical-align: top;
    font-size: 9px;
}
.main-title {
    font-size: 14px;
    font-weight: bold;
    margin: 5px 0;
    color: #2d4a2b;
}
.sub-title {
    font-size: 11px;
    font-weight: bold;
    margin: 3px 0;
    color: #2d4a2b;
}
.section-title {
    background: #f0f0f0;
    border: 1px solid #8b7355;
    padding: 8px;
    text-align: center;
    font-weight: bold;
    margin-top: 5px;
}
.info-row {
    background: #ffffff;
    border: 1px solid #8b7355;
    padding: 5px;
}
table.data-table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 10px; 
}
table.data-table th, 
table.data-table td { 
    border: 1px solid #8b7355; 
    padding: 6px; 
    text-align: left; 
}
table.data-table th { 
    background: #2d5016; 
    color: white; 
    text-align: center; 
    font-weight: bold;
    font-size: 10px;
}
tr.subsector td { 
    background: #4a7c39; 
    color: white; 
    font-weight: bold; 
    font-size: 10px; 
}
tr.grupo td { 
    background: #6b9e5a; 
    color: white; 
    font-weight: 600; 
}
tr.subgrupo td { 
    background: #a8d08d; 
    color: #2d4a2b; 
    font-weight: 500; 
}
</style>
</head>
<body>

<!-- ENCABEZADO INSTITUCIONAL -->
<table class="header-table">
    <tr>
        <td class="logo-cell">
            <!-- Logo opcional: descomentar si existe -->
            <!-- <img src="{{ public_path('images/logo.png') }}" width="60"> -->
            <div style="width:60px; height:60px; border:2px solid #ccc; margin:0 auto;"></div>
        </td>
        <td class="title-cell">
            <div class="main-title">MINISTERIO DE AGRICULTURA</div>
            <div class="sub-title">REPORTE DE CULTIVOS AGRÍCOLAS</div>
        </td>
        <td class="date-cell">
            Fecha: {{ now()->format('d/m/Y') }}<br>
            {{ now()->format('H:i') }}
        </td>
    </tr>
</table>

<!-- INFORMACIÓN DEL REPORTE -->
<div class="section-title">REPORTE DE CULTIVOS</div>
<div class="info-row">Generado: {{ $fecha ?? now()->format('d/m/Y H:i') }}</div>

<!-- TABLA DE DATOS -->
<table class="data-table">
    <thead>
        <tr>
            <th>Clasificación</th>
            <th>Descripción</th>
            <th>Código</th>
        </tr>
    </thead>
    <tbody>
    @forelse($subsectores as $ss)
        <!-- SUB SECTOR -->
        <tr class="subsector">
            <td>SUB SECTOR</td>
            <td>{{ $ss->descripcion }}</td>
            <td>{{ $ss->codigo }}</td>
        </tr>

        @foreach($ss->grupos as $g)
            <!-- GRUPO -->
            <tr class="grupo">
                <td>GRUPO</td>
                <td>{{ $g->descripcion }}</td>
                <td>{{ $g->codigo }}</td>
            </tr>

            @foreach($g->subgrupos as $sg)
                <!-- SUB GRUPO -->
                <tr class="subgrupo">
                    <td>SUB GRUPO</td>
                    <td>{{ $sg->descripcion }}</td>
                    <td>{{ $sg->codigo }}</td>
                </tr>

                <!-- CULTIVOS -->
                @foreach($sg->cultivos as $c)
                    <tr>
                        <td>CULTIVO</td>
                        <td>{{ $c->descripcion }}</td>
                        <td>{{ $c->codigo }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    @empty
        <tr>
            <td colspan="3" style="text-align:center;">Sin resultados</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
