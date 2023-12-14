<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TareosController extends Controller
{
    public function insertarTareos(Request $request)
    {
        $params = [
            json_encode(['tareos' => $request['tareos']])
        ];

        $logParams = [
            $request["mac"],
            $request["user_login"],
            $request["app"],
            $request["descripcion"],
            $request["parametros"]
        ];

        try {
            $data = DB::select("SET NOCOUNT ON; EXEC DataGreenMovil..sp_Dgm_Tareos_TransferirTareo_V2 ?;", $params);
            // return response()->json();
            DB::statement("insert into Datagreen..Logs values(GETDATE(), ?, ?, ?, ?, ?)",$logParams);
            return ['code' => 200, 'response' => $data];
        } catch (\Throwable $th) {
            //throw $th;
            return ['code' => 500, 'response' => strval($th)];
        }
    }

    public function getLogs()
    {
        return DB::select("SELECT TOP 100 * FROM " . env("DATAGREEN") . "..Logs WHERE Parametros LIKE '%000000000EAX%' ORDER BY 1;");
    }
}
