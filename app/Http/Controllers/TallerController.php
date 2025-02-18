<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TallerController extends Controller
{
    public function insertarParteMaquinaria(Request $request){
        return $request;
    }

    public function getMaquinarias(){
        $data = DB::select('EXEC DataGreenMovil..SP_OBTENER_MAQUINARIA;');

        $results = collect($data);
        return ["response"=>$results];
    }
    
    public function getImplementos(){
        $data = DB::select('EXEC DataGreenMovil..SP_OBTENER_IMPLEMENTO;');

        $results = collect($data);
        return ["response"=>$results];
    }

    public function getCombustibles(){
        $data = DB::select('EXEC DataGreenMovil..SP_OBTENER_COMBUSTIBLES;');

        $results = collect($data);
        return ["response"=>$results];
    }
    
    public function getOperarios(){
        $data = DB::select('EXEC DataGreenMovil..SP_OBTENER_OPERARIO;');

        $results = collect($data);
        return ["response"=>$results];
    }
}
