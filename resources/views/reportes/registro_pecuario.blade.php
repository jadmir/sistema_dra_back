<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Formulario de Registro Productivo</title>
<style>
    @page {
        margin-top: 40px;
        margin-bottom: 20px;
        margin-left: 25px;
        margin-right: 25px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8px;
        margin: 0;
    }

    .titulo-principal {
        text-align: center;
        font-weight: bold;
        font-size: 11px;
        margin-bottom: 2px;
    }

    .subtitulo {
        text-align: center;
        font-size: 9px;
        margin-bottom: 10px;
    }

    .mini-cuadro {
        width: 100%;
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 8px;
    }

    .mini-cuadro th {
        border: 1px solid black;
        padding: 2px;
        text-align: center;
        font-weight: bold;
    }

    .mini-cuadro td {
        border: 1px solid black;
        height: 14px;
        text-align: center;
        padding: 1px;
    }

    .fila-cuadros {
        width: 100%;
        border: none;
        margin-top: 2px;
    }

    .celda {
        vertical-align: top;
        padding: 2px;
    }

    .seccion {
        font-weight: bold;
        background: #ddd;
        text-transform: uppercase;
        padding: 4px;
        font-size: 8px;
        text-align:left;
    }

    .valor {
        font-weight: bold;
        text-align: center;
    }

    table {
        border-collapse: collapse;
    }

    .tabla-grande td,
    .tabla-grande th {
        border: 1px solid #000;
    }
    
</style>
</head>
<body>

    <!-- Títulos principales -->
    <div class="titulo-principal">ESTABLO LECHERO</div>
    <div class="subtitulo">FORMULARIO DE REGISTRO PRODUCTIVO</div>

    <table class="fila-cuadros">
    <tr>
        <td class="celda" width="18%">
            <table class="mini-cuadro">
                <tr><th>CÓDIGO DEL ESTABLO</th></tr>
                <tr><td>{{ $registro->codigo_establo ?? '' }}</td></tr>
            </table>
        </td>

        <td class="celda" width="18%">
            <table class="mini-cuadro">
                <tr><th>UBIGEO</th></tr>
                <tr><td>{{ $registro->ubigeo ?? '' }}</td></tr>
            </table>
        </td>

        <td width="28%"></td>

        <td class="celda" width="18%">
            <table class="mini-cuadro">
                <tr><th>MES DE REFERENCIA</th></tr>
                <tr><td>{{ $registro->mes_de_referencia ?? '' }}</td></tr>
            </table>
        </td>

        <td class="celda" width="18%">
            <table class="mini-cuadro">
                <tr><th>AÑO</th></tr>
                <tr><td>{{ $registro->anio ?? '' }}</td></tr>
            </table>
        </td>
    </tr>
    </table>

    <!-- CAP I -->
    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:10px;">
    <tr>
        <th colspan="4" class="seccion">CAP. I – UBICACIÓN E IDENTIFICACIÓN DEL ESTABLO</th>
    </tr>
    <tr>
        <td style="width:15%;">Región:</td>
        <td style="width:35%;" class="valor">{{ $registro->region ?? '' }}</td>
        <td style="width:20%;">Nombre del Establo:</td>
        <td style="width:30%;" class="valor">{{ $registro->nombre_establo ?? '' }}</td>
    </tr>
    <tr>
        <td>Provincia:</td>
        <td class="valor">{{ $registro->provincia ?? '' }}</td>
        <td>Productor/Razón Social:</td>
        <td class="valor">{{ $registro->producto_razon_social ?? '' }}</td>
    </tr>
    <tr>
        <td>Distrito:</td>
        <td class="valor">{{ $registro->distrito ?? '' }}</td>
        <td>Dirección:</td>
        <td class="valor">{{ $registro->direccion ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td>RUC:</td>
        <td class="valor">{{ $registro->ruc ?? '' }}</td>
    </tr>
    </table>

    <!-- CAP II -->
    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:10px;">
    <tr>
        <th colspan="9" class="seccion">CAP. II – POBLACIÓN DE VACUNOS EN EL ÚLTIMO DÍA DEL MES DE REFERENCIA</th>
    </tr>
    <tr>
        <td colspan="9" style="text-align:center; padding:8px;">
            <table style="margin:0 auto; border-collapse:collapse;">
                <tr>
                    <td style="font-weight:bold; padding:5px 10px; border:none;">Población total de Vacunos</td>
                    <td style="padding-left:10px; border:none;">
                        <table style="border:1px solid #000; border-collapse:collapse;">
                            <tr><td style="padding:10px 45px; font-weight:bold; text-align:center; min-width:50px; min-height:20px;">{{ $animalTotal->total_animal ?? '' }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th colspan="9" class="seccion">Población por clase</th>
    </tr>
    </table>

    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:-1px;">
    @if(!empty($animales) && count($animales) > 0)
    <tr>
        @foreach($animales as $animal)
            <th style="padding:4px; background:#fafafa;">{{ $animal->variedad }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach($animales as $animal)
            <td style="padding:4px; text-align:center;">{{ $animal->total }}</td>
        @endforeach
    </tr>
    @else
    <tr>
        <td colspan="9" style="text-align:center; font-size:10px; padding:4px;">No hay datos de población registrados.</td>
    </tr>
    @endif
    </table>

    <!-- CAP III -->
    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:10px;">
    <tr>
        <th colspan="6" class="seccion">CAP. III – PRODUCCIÓN Y DESTINO DE LA LECHE FRESCA DURANTE EL MES DE REFERENCIA</th>
    </tr>
    <tr>
        <td colspan="6" style="text-align:center; padding:8px;">
            <table style="margin:0 auto; border-collapse:collapse;">
                <tr>
                    <td style="font-weight:bold; padding:5px 10px; border:none;">Producción total de leche fresca</td>
                    <td style="padding-left:10px; border:none;">
                        <table style="border:1px solid #000;">
                            <tr><td style="padding:10px 45px; font-weight:bold; text-align:center; min-width:50px; min-height:20px;">{{ $lecheTotal->total_leche ?? '' }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th colspan="6" class="seccion">Destino de la producción durante el mes de referencia y precio en establo de la leche fresca en la última venta del mes de referencia.</th>
    </tr>
    <tr style="text-align:center; font-weight:bold;">
        <td width="30%">Destino</td>
        <td width="15%">Cantidad (Lt)</td>
        <td width="15%">Precio sin IGV (S/. x Lt)</td>
        <td width="30%">Destino</td>
        <td width="15%">Cantidad (Lt)</td>
        <td width="15%">Precio sin IGV (S/. x Lt)</td>
    </tr>

    @php $totalDestinos = count($destinos); @endphp
    @if($totalDestinos>0)
        @for($i=0;$i<$totalDestinos;$i+=2)
        <tr style="text-align:center;">
            <td style="text-align:left;">{{ $i+1 }}. {{ $destinos[$i]->destino ?? '' }}</td>
            <td>{{ $destinos[$i]->cantidad ?? '' }}</td>
            <td>{{ $destinos[$i]->precio ?? '' }}</td>
            @if(isset($destinos[$i+1]))
                <td style="text-align:left;">{{ $i+2 }}. {{ $destinos[$i+1]->destino ?? '' }}</td>
                <td>{{ $destinos[$i+1]->cantidad ?? '' }}</td>
                <td>{{ $destinos[$i+1]->precio ?? '' }}</td>
            @else
                <td colspan="3">&nbsp;</td>
            @endif
        </tr>
        @endfor
    @else
    <tr>
        <td colspan="6" style="text-align:center; color:#666;">No hay destinos registrados.</td>
    </tr>
    @endif
    </table>

    <!-- CAP IV -->
    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:10px;">
    <tr>
        <th colspan="8" class="seccion">CAP. IV – SACA DURANTE EL MES DE REFERENCIA</th>
    </tr>
    <tr>
        <td colspan="8" style="text-align:center; padding:8px;">
            <table style="margin:0 auto; border-collapse:collapse;">
                <tr>
                    <td style="font-weight:bold; padding:5px 10px; border:none;">Saca total de Vacunos</td>
                    <td style="padding-left:10px; border:none;">
                        <table style="border:1px solid #000;">
                            <tr><td style="padding:10px 45px; font-weight:bold; text-align:center; min-width:50px; min-height:20px;">{{ $sacaTotal->total_leche ?? '' }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr style="background:#f2f2f2; text-align:center; font-weight:bold;">
        <td colspan="4">Saca de Reproductores</td>
        <td colspan="4">Saca de Vacunos de Descarte</td>
    </tr>
    <tr style="text-align:center; font-weight:bold;">
        <td>Clase</td>
        <td>Saca<br>(Unidades)</td>
        <td colspan="2">Precio de venta<br>(S/. x animal)</td>
        <td>Clase</td>
        <td>Saca<br>(Unidades)</td>
        <td>Peso promedio<br>en pie (Kg/animal)</td>
        <td>Precio de venta<br>(S/. x animal)</td>
    </tr>

    @php $maxFilas = max(count($sacaReproductores ?? []), count($sacas ?? [])); @endphp
    @if($maxFilas>0)
        @for($i=0;$i<$maxFilas;$i++)
        <tr style="text-align:center;">
            @if(isset($sacaReproductores[$i]))
                <td style="text-align:left;">{{ $i+1 }}. {{ $sacaReproductores[$i]->variedad ?? '—' }}</td>
                <td>{{ $sacaReproductores[$i]->saca_unidad ?? '—' }}</td>
                <td colspan="2">{{ $sacaReproductores[$i]->precio_venta ?? '—' }}</td>
            @else
                <td>&nbsp;</td><td>&nbsp;</td><td colspan="2">&nbsp;</td>
            @endif
            @if(isset($sacas[$i]))
                <td style="text-align:left;">{{ $i+1 }}. {{ $sacas[$i]->variedad ?? '—' }}</td>
                <td>{{ $sacas[$i]->saca_unidad ?? '—' }}</td>
                <td>{{ $sacas[$i]->peso_promedio_vivo ?? '—' }}</td>
                <td>{{ $sacas[$i]->precio_venta ?? '—' }}</td>
            @else
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            @endif
        </tr>
        @endfor
    @else
    <tr>
        <td colspan="8" style="text-align:center; color:#666;">No hay registros de saca disponibles.</td>
    </tr>
    @endif
    </table>

    <!-- CAP V NATALIDAD Y MORTALIDAD -->
    <table class="tabla-grande" width="100%" style="border-collapse: collapse; margin-top:15px;">
    <tr>
        <th colspan="6" class="seccion">CAP. V: NATALIDAD Y MORTALIDAD DURANTE EL MES DE REFERENCIA</th>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center; background:#f2f2f2;">Natalidad</td>
        <td colspan="3" style="text-align:center; background:#f2f2f2;">Mortalidad</td>
    </tr>
    <tr style="text-align:center; font-weight:bold; background:#f9f9f9;">
        <td>Concepto</td>
        <td colspan="2">Cantidad (unidades)</td>
        <td>Variedad</td>
        <td colspan="2">Cantidad (unidades)</td>
    </tr>

    @php $maxFilas = max(count($natalidad ?? []), count($mortalidad ?? [])); @endphp
    @if($maxFilas>0)
        @for($i=0;$i<$maxFilas;$i++)
        <tr style="text-align:center;">
            @if(isset($natalidad[$i]))
                <td style="text-align:left;">{{ $natalidad[$i]->concepto ?? '' }}</td>
                <td colspan="2">{{ $natalidad[$i]->cantidad ?? '' }}</td>
            @else
                <td>&nbsp;</td><td colspan="2">&nbsp;</td>
            @endif
            @if(isset($mortalidad[$i]))
                <td style="text-align:left;">{{ $mortalidad[$i]->variedad ?? '' }}</td>
                <td colspan="2">{{ $mortalidad[$i]->cantidad ?? '' }}</td>
            @else
                <td>&nbsp;</td><td colspan="2">&nbsp;</td>
            @endif
        </tr>
        @endfor
    @else
    <tr>
        <td colspan="6" style="text-align:center; color:#666;">No hay registros de natalidad ni mortalidad disponibles.</td>
    </tr>
    @endif
    </table>

    <!-- CAP VI  OBSERVACIONES -->
    <table class="tabla-grande" width="100%" style="border-collapse: collapse; margin-top:15px;">
    <tr>
        <th class="seccion">VI. OBSERVACIONES</th>
    </tr>
    <tr>
        <td style="padding:6px; text-align:left;">{{ $informe->observaciones ?? 'Sin observaciones' }}</td>
    </tr>
    </table>

    <!-- CAP VIII INFORME TÉCNICO -->
    <table class="tabla-grande" style="width:100%; border:1px solid #000; margin-top:10px;">
    <tr>
        <th colspan="4" class="seccion">CAP. VIII – INFORME TECNICO</th>
    </tr>
    <tr>
        <td style="width:15%;">Informante:</td>
        <td style="width:35%;" class="valor">{{ $informe->informante ?? '' }}</td>
        <td style="width:20%;">Tecnico:</td>
        <td style="width:30%;" class="valor">{{ $informe->tecnico ?? '' }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td class="valor">{{ $informe->email ?? '' }}</td>
        <td>Fecha:</td>
        <td class="valor">{{ $informe->fecha ?? '' }}</td>
    </tr>
    <tr>
        <td>Telefono:</td>
        <td class="valor">{{ $informe->telefono ?? '' }}</td>
        <td>Crago:</td>
        <td class="valor">{{ $informe->cargo ?? '' }}</td>
    </tr>
    </table>

</body>
</html>
