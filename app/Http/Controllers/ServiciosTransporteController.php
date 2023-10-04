<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ServiciosTransporteController extends Controller
{
    public function getServiciosTransporte(){
        $data = DB::unprepared("select * from mst_usuarios");
        return $data;
    }
}
