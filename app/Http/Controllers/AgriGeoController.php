<?php

namespace App\Http\Controllers;

use App\Models\AgriRegion;
use App\Models\AgriProvincia;
use App\Models\AgriDistrito;

class AgriGeoController extends Controller
{
    public function regiones()
    {
        return response()->json([
            'message' => 'Listado de regiones',
            'data' => AgriRegion::orderBy('nombre')->get()
        ]);
    }

    public function provincias($region_id)
    {
        return response()->json([
            'message' => 'Listado de provincias',
            'data' => AgriProvincia::where('region_id', $region_id)->orderBy('nombre')->get()
        ]);
    }

    public function distritos($provincia_id)
    {
        return response()->json([
            'message' => 'Listado de distritos',
            'data' => AgriDistrito::where('provincia_id', $provincia_id)->orderBy('nombre')->get()
        ]);
    }
}
