<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteRegistroPecuarioController extends Controller
{
    public function pdf($id)
    {
        try {
            //Registro principal
            $registro = DB::table('agri_registro_pecuarios')->where('id', $id)->first();
            if (!$registro) {
                return response()->json(['message' => 'Registro no encontrado.'], 404);
            }

            //Animales asociados
            $animales = DB::table('agri_animales AS a')
                ->join('agri_variedad_animal AS v', 'v.id', '=', 'a.variedad_id')
                ->select('v.nombre AS variedad', 'a.total')
                ->where('a.registro_pecuario_id', $id)
                ->get();

            //Totales
            $animalTotal = DB::table('animal_total')->where('registro_pecuario_id', $id)->first();
            $lecheTotal  = DB::table('leche_fresca')->where('registro_pecuario_id', $id)->first();
            $sacaTotal = DB::table('agri_saca_total')->where('id_agri_registro_pecuario', $id)->first();

            // Destinos de leche
            $destinos = DB::table('agri_producto_leches AS pl')
                ->leftJoin('agri_destinos AS d', 'd.id', '=', 'pl.agri_destinos_id')
                ->select('pl.*', 'd.nombre AS destino')
                ->where('pl.registro_pecuario_id', $id)
                ->get();

            //Saca de animales
            $sacas = DB::table('saca_vacuno_descarte AS s')
                ->join('agri_variedad_animal AS v', 'v.id', '=', 's.id_agri_variedad_animal')
                ->select('v.nombre AS variedad', 's.saca_unidad', 's.precio_venta', 's.peso_promedio_vivo')
                ->where('s.id_agri_registro_pecuario', $id)
                ->get();

            //Saca de reproductores
            $sacaReproductores = DB::table('saca_reproduccion AS s')
                ->join('agri_variedad_animal AS v', 'v.id', '=', 's.id_agri_variedad_animal')
                ->select('v.nombre AS variedad', 's.saca_unidad', 's.precio_venta')
                ->where('s.id_agri_registro_pecuario', $id)
                ->get();

      

            //Natalidad
            $natalidad = DB::table('agri_natalidad AS n')
                ->join('agri_natalidad_mortalidad AS nm', 'nm.id', '=', 'n.natalidad_mortalidad_id')
                ->select('nm.concepto', 'n.cantidad')
                ->where('n.id_agri_registro_pecuario', $id)
                ->get();

            //Mortalidad
            $mortalidad = DB::table('agri_mortalidad AS m')
                ->join('agri_variedad_animal AS v', 'v.id', '=', 'm.id_agri_variedad_animal')
                ->select('v.nombre AS variedad', 'm.cantidad')
                ->where('m.id_agri_registro_pecuario', $id)
                ->get();

            //Informe técnico
            $informe = DB::table('informe_tecnico')
                ->where('id_agri_registro_pecuario', $id)
                ->first();

            //Datos para la vista
            $data = [
                'registro'          => $registro,
                'animales'          => $animales,
                'animalTotal'       => $animalTotal,
                'lecheTotal'        => $lecheTotal,
                'sacaTotal'         => $sacaTotal,
                'destinos'          => $destinos,
                'sacas'             => $sacas,
                'sacaReproductores' => $sacaReproductores,
                'natalidad'         => $natalidad,
                'mortalidad'        => $mortalidad,
                'informe'           => $informe,
                'fecha'             => now()->format('d/m/Y H:i'),
            ];

            //Renderizar vista PDF o vista web
            if (request()->has('view')) {
                return view('reportes.registro_pecuario', $data);
            }

            $pdf = Pdf::loadView('reportes.registro_pecuario', $data)
                ->setPaper('a4', 'portrait');

            return $pdf->stream("reporte_registro_pecuario_{$id}.pdf");

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al generar el reporte del registro pecuario.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
