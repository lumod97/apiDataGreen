<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonasController extends Controller
{
    public function obtenerPersonasConObservacion(){
        $data = DB::select("exec DataGreen..sp_Cns_DescargarPersonas 1;");
        return ['code' => 200, 'response' => $data];
    }
}
