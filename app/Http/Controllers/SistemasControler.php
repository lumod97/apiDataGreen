<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemasControler extends Controller
{
    public function obtenerUsuarios(Request $request){
        return DB::select('EXEC DataGreenMovil..sp_obtener_usuarios_minigreen');
    }
}
