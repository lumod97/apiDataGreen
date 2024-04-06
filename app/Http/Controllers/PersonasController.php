<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonasController extends Controller
{
    public function obtenerPersonasConObservacion()
    {
        $data = DB::select("exec DataGreen..sp_Cns_DescargarPersonas 1;");
        return ['code' => 200, 'response' => $data];
    }

    public function obtenerDataPersona(Request $request)
    {

        $params = [
            $request['dni']
        ];

        $data = DB::select("SELECT
        IDCODIGOGENERAL idcodigogeneral,
        NRODOCUMENTO dni,
        CONCAT(TRIM(NOMBRES), ' ', TRIM(A_PATERNO), ' ', TRIM(A_MATERNO)) nombresApellidos,
        SEXO sexo,
        PROCEDENCIA procedencia,
        DESCRIPCION_VIA direccion,
        CASE
            WHEN ESTADO = 1 THEN
                'ACTIVO'
            ELSE 'INACTIVO'
        END estado,
        'EMP' planilla,
        CASE
            WHEN AUTOGENERADOAFP = '' OR AUTOGENERADOAFP = NULL THEN
                'OBTENER CUSSP'
            ELSE AUTOGENERADOAFP
        END cusp,
        LISTA_NEGRA observado,
        'NO' rendimiento
    FROM AgricolaSanJuan_2020..PERSONAL_GENERAL PG WHERE pg.NRODOCUMENTO=?", $params);
        return  $data;
    }
}
