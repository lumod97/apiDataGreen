<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcasController extends Controller
{
    public function transferirMarcas(Request $request)
    {
        try {
            $jsonMarcas = json_encode(['marcas' => $request['marcas']]);
            $data = DB::statement("EXEC DatagreenMovil_TestEnviroment..sp_CnsMobile_insertar_marcas ?;", [$jsonMarcas]);
            return ['code' => 200, 'newId' => "nada mano", 'response' => strval("AGREGADO CORRECTAMENTE")];
        } catch (\Throwable $th) {
            throw $th;
        }
        // return $data;
        // return ['code' => 200, 'newId' => "nada mano", 'response' => $data];
    }
}

// Illuminate\Database\QueryException: SQLSTATE[07002]: [Microsoft][ODBC Driver 17 for SQL Server]COUNT field incorrect or syntax error (SQL: EXEC DataGreenMovil_TestEnviroment..sp_CnsMobile_insertar_marcas ?;) in file C:\Users\Programador02\apiDataGreen\vendor\laravel\framework\src\Illuminate\Database\Connection.php on line 760
