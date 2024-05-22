<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiniGreenController extends Controller
{
    public function validarVersion(){
        $data = DB::select("select ISNULL(MAX(id), '0.0.0') last_id FROM DataGreenMovil..LogVersionesMiniGreen");
        return $data[0]->last_id;
    }

    // THIS FUNCTION IS DEPRECATED
    public function devolverRutaActualizacion(){
        $rutaArchivo = "http://192.168.30.99:8090/DataGreenMovil/MiniGreen1.7.0.apk";
        $nombreArchivo = 'nombre_archivo.apk';
        return response()->download($rutaArchivo, $nombreArchivo);
    }

    public function registrarActualización(Request $request){
        $params = [
            $request['version'],
            $request['usuario'],
            $request['changelog']
        ];

        return DB::statement("INSERT INTO DataGreenMovil..LogVersionesMiniGreen VALUES ( ? ,GETDATE(), GETDATE(), ?, ?)", $params);
    }
}