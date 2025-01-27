<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiniGreenController extends Controller
{
    public function validarVersion()
    {
        $data = DB::select("WITH SplitVersions AS (
    SELECT 
        id,
        CAST(PARSENAME(id, 4) AS INT) AS major,
        CAST(PARSENAME(id, 3) AS INT) AS minor,
        CAST(PARSENAME(id, 2) AS INT) AS patch,
        CAST(PARSENAME(id, 1) AS INT) AS build
    FROM DataGreenMovil..LogVersionesMiniGreen
)
SELECT TOP 1 id AS last_id
FROM SplitVersions
ORDER BY major DESC, minor DESC, patch DESC, build DESC;
");
        return $data[0]->last_id;
    }

    // THIS FUNCTION IS DEPRECATED
    public function devolverRutaActualizacion()
    {
        $rutaArchivo = "http://192.168.30.99:8090/DataGreenMovil/MiniGreen1.7.0.apk";
        $nombreArchivo = 'nombre_archivo.apk';
        return response()->download($rutaArchivo, $nombreArchivo);
    }

    public function registrarActualización(Request $request)
    {
        $params = [
            $request['version'],
            $request['usuario'],
            $request['changelog']
        ];

        return DB::statement("INSERT INTO DataGreenMovil..LogVersionesMiniGreen VALUES ( ? ,GETDATE(), GETDATE(), ?, ?)", $params);
    }
}
