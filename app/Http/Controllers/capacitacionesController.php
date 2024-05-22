<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class capacitacionesController extends Controller
{
    public function getAreas(){
        $data = DB::select('SELECT TOP 3 TRIM(IDAREA) idarea, TRIM(DESCRIPCION) descripcion FROM AgricolaSanJuan_2020..AREAS WHERE ESTADO = 1');
        return $data;
    }

    public function getPersonas(){
        $data = DB::select('EXEC DataGreenMovil..sp_descargar_personas_con_area');
        return $data;
    }
}
