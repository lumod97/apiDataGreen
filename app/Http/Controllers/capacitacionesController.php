<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class capacitacionesController extends Controller
{
    public function getAreas(){
        $data = DB::select('SELECT TRIM(IDAREA) idarea, TRIM(DESCRIPCION) descripcion FROM AgricolaSanJuan_2020..AREAS WHERE ESTADO = 1');
        return $data;
    }

    public function getPersonas(){
        $data = DB::select('EXEC DataGreenMovil..sp_descargar_personas_con_area');
        return $data;
    }

    public function getCargos(){
        $data = DB::select('SELECT IDCARGO idcargo, DESCRIPCION descripcion from AgricolaSanJuan_2020..CARGOS_PERSONAL where estado = 1');
        return $data;
    }

    public function getTiposCapacitacion(){
        $data = DB::select('SELECT id, description descripcion FROM DataGreenMovil..sst_tipos_capacitacion');
        return $data;
    }

    public function getCapacitaciones(){
        $data = DB::select('SELECT id, [description] descripcion, tipo, fecha, capacitador_id, horas_estimadas, created_by, created_at, updated_by, updated_at from DataGreenMovil..sst_capacitaciones
        ');
        return $data;
    }
}
